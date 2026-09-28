<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Penggajian;

class SalaryIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * INTEGRATION TESTING: Menguji alur dari Admin klik 'Simpan Gaji'
     * sampai data tersebut muncul di halaman dashboard akun Pegawai.
     */
    public function test_alur_admin_simpan_gaji_sampai_tampil_di_pegawai(): void
    {
        // 1. Setup: Membuat data Jabatan
        $jabatan = Jabatan::create([
            'nama_jabatan' => 'Staff IT',
            'gapok' => 6000000,
            'tunjangan' => 1500000,
        ]);

        // 2. Setup: Membuat User Admin
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admintest@majujaya.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 3. Setup: Membuat User Pegawai
        $pegawai = User::create([
            'name' => 'Pegawai Test',
            'email' => 'pegawaitest@majujaya.com',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'jabatan_id' => $jabatan->id,
        ]);

        // 4. Action: Login sebagai Admin dan simpan data gaji pegawai
        $response = $this->actingAs($admin)
            ->post('/admin/penggajian', [
                'user_id' => $pegawai->id,
                'bulan' => 'Agustus',
                'tahun' => 2026,
                'potongan_absen' => 300000,
            ]);

        // Assert: Redirect berhasil ke halaman dashboard admin
        $response->assertRedirect('/admin/dashboard');

        // Assert: Data gaji berhasil masuk ke database
        // Gaji Bersih = (6.000.000 + 1.500.000) - 300.000 = 7.200.000
        $this->assertDatabaseHas('penggajians', [
            'user_id' => $pegawai->id,
            'bulan' => 'Agustus',
            'tahun' => 2026,
            'potongan_absen' => 300000,
            'total_gaji' => 7200000,
        ]);

        // 5. Action: Login sebagai Pegawai dan buka halaman dashboard pegawai
        $responseDashboard = $this->actingAs($pegawai)
            ->get('/pegawai/dashboard');

        // Assert: Status request OK (200)
        $responseDashboard->assertStatus(200);

        // Assert: Informasi gaji bulan Agustus dan total_gaji tampil di dashboard pegawai
        $responseDashboard->assertSee('Agustus');
        $responseDashboard->assertSee(number_format(7200000, 0, ',', '.'));
    }
}
