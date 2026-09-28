<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Jabatans
        $manager = Jabatan::create([
            'nama_jabatan' => 'Manager',
            'gaji_pokok' => 8000000,
            'tunjangan' => 2000000,
        ]);

        $staff = Jabatan::create([
            'nama_jabatan' => 'Staff',
            'gaji_pokok' => 5000000,
            'tunjangan' => 1000000,
        ]);

        // 2. Seed Admin Pegawai
        Pegawai::create([
            'name' => 'Admin Maju Jaya',
            'email' => 'admin@majujaya.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'jabatan_id' => null,
        ]);

        // 3. Seed Pegawai/Employees
        Pegawai::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@majujaya.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'jabatan_id' => $staff->id,
        ]);

        Pegawai::create([
            'name' => 'Ani Wijaya',
            'email' => 'ani@majujaya.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'jabatan_id' => $manager->id,
        ]);
    }
}
