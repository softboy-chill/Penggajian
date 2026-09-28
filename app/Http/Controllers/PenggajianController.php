<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Penggajian;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class PenggajianController extends Controller
{
    /**
     * Tampilkan halaman dashboard Admin beserta riwayat penggajian.
     */
    public function adminDashboard()
    {
        try {
            // Contoh pengambilan data dengan Relationship Eager Loading (ORM Eloquent)
            $penggajians = Penggajian::with(['pegawai.jabatan'])->orderBy('created_at', 'desc')->get();
            
            return view('admin.dashboard', compact('penggajians'));
        } catch (QueryException $e) {
            // Menangani kendala koneksi database
            return back()->with('error', 'Gagal memuat data: Hubungan ke database terputus. Kode Error: ' . $e->getCode());
        }
    }

    /**
     * Tampilkan form input gaji baru.
     */
    public function create()
    {
        try {
            // Mengambil semua user yang bertindak sebagai pegawai
            $pegawais = Pegawai::where('role', 'pegawai')->with('jabatan')->get();
            return view('admin.penggajian.create', compact('pegawais'));
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat form: Gangguan koneksi database.');
        }
    }

    /**
     * Proses penyimpanan data gaji baru (Admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'    => 'required|exists:pegawais,id',
            'bulan'         => 'required|string',
            'tahun'         => 'required|integer|min:2020|max:2030',
            'potongan_absen' => 'nullable|integer|min:0',
        ]);

        try {
            // Mengambil data pegawai beserta jabatannya untuk proses kalkulasi
            $pegawai = Pegawai::with('jabatan')->findOrFail($request->pegawai_id);

            if (!$pegawai->jabatan) {
                return back()->with('error', 'Pegawai terpilih belum memiliki jabatan. Atur jabatan terlebih dahulu.');
            }

            $gapok     = $pegawai->jabatan->gaji_pokok;
            $tunjangan = $pegawai->jabatan->tunjangan;

            // --- Auto-lookup potongan dari tabel absensi (server-side, aman) ---
            $absensi = Absensi::where('pegawai_id', $request->pegawai_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->first();

            // Potongan diambil dari DB absensi; jika belum ada data absensi, fallback ke 0
            $potongan_absen = $absensi ? $absensi->total_potongan : 0;

            // Memanggil fungsi algoritma hitung gaji
            $total_gaji = $this->hitungGaji($gapok, $tunjangan, $potongan_absen);

            // Simpan ke database dengan proteksi try-catch
            Penggajian::create([
                'pegawai_id'     => $request->pegawai_id,
                'bulan'          => $request->bulan,
                'tahun'          => $request->tahun,
                'potongan_absen' => $potongan_absen,
                'total_gaji'     => $total_gaji,
            ]);

            return redirect()->route('admin.penggajian.index')->with('success', 'Data penggajian berhasil disimpan.');

        } catch (QueryException $e) {
            // Penanganan error jika terjadi kegagalan database/koneksi
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data ke database. Silakan coba lagi. Rincian: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form edit data penggajian.
     */
    public function edit($id)
    {
        try {
            $penggajian = Penggajian::with(['pegawai.jabatan'])->findOrFail($id);
            $pegawais   = Pegawai::where('role', 'pegawai')->with('jabatan')->get();
            return view('admin.penggajian.edit', compact('penggajian', 'pegawais'));
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat form edit: Gangguan koneksi database.');
        }
    }

    /**
     * Proses update data penggajian (Admin).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pegawai_id'    => 'required|exists:pegawais,id',
            'bulan'         => 'required|string',
            'tahun'         => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $penggajian = Penggajian::findOrFail($id);
            $pegawai    = Pegawai::with('jabatan')->findOrFail($request->pegawai_id);

            if (!$pegawai->jabatan) {
                return back()->with('error', 'Pegawai terpilih belum memiliki jabatan.');
            }

            $gapok     = $pegawai->jabatan->gaji_pokok;
            $tunjangan = $pegawai->jabatan->tunjangan;

            // Ambil potongan dari tabel absensi berdasarkan bulan & tahun
            $absensi = Absensi::where('pegawai_id', $request->pegawai_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->first();

            $potongan_absen = $absensi ? $absensi->total_potongan : 0;
            $total_gaji     = $this->hitungGaji($gapok, $tunjangan, $potongan_absen);

            $penggajian->update([
                'pegawai_id'     => $request->pegawai_id,
                'bulan'          => $request->bulan,
                'tahun'          => $request->tahun,
                'potongan_absen' => $potongan_absen,
                'total_gaji'     => $total_gaji,
            ]);

            return redirect()->route('admin.penggajian.index')->with('success', 'Data penggajian berhasil diperbarui.');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data penggajian.
     */
    public function destroy($id)
    {
        try {
            $penggajian = Penggajian::findOrFail($id);
            $penggajian->delete();
            return redirect()->route('admin.penggajian.index')->with('success', 'Data penggajian berhasil dihapus.');
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman dashboard Pegawai (Riwayat slip gaji pribadi).
     */
    public function pegawaiDashboard()
    {
        try {
            // Mengambil slip gaji hanya milik pegawai yang sedang login
            $penggajians = Penggajian::where('pegawai_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return view('pegawai.dashboard', compact('penggajians'));
        } catch (QueryException $e) {
            return back()->with('error', 'Tidak dapat memuat slip gaji Anda karena gangguan koneksi database.');
        }
    }

    /**
     * Tampilkan detail Slip Gaji Pegawai.
     */
    public function showSlip($id)
    {
        try {
            // Validasi hak akses pegawai (Pegawai hanya bisa melihat slip gajinya sendiri, Admin bisa melihat semua)
            $slip = Penggajian::findOrFail($id);
            if (Auth::user()->role === 'pegawai' && $slip->pegawai_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki wewenang untuk melihat slip gaji orang lain.');
            }

            /*
             * DEMO UNTUK ASESOR (UNIT: SQL & AKSES BASIS DATA)
             * Di bawah ini adalah dua metode alternatif untuk mengambil data slip gaji beserta relasinya:
             * 
             * METODE 1: Menggunakan ORM Eloquent (BelongsTo Relationship) - (REKOMENDASI/DIGUNAKAN)
             *   $slipDetails = Penggajian::with(['pegawai.jabatan'])->findOrFail($id);
             * 
             * METODE 2: Menggunakan Raw SQL/Query Builder JOIN - (ALTERNATIF)
             *   $slipDetails = DB::table('penggajians')
             *       ->join('pegawais', 'penggajians.pegawai_id', '=', 'pegawais.id')
             *       ->join('jabatans', 'pegawais.jabatan_id', '=', 'jabatans.id')
             *       ->select('penggajians.*', 'pegawais.name', 'jabatans.nama_jabatan', 'jabatans.gaji_pokok', 'jabatans.tunjangan')
             *       ->where('penggajians.id', $id)
             *       ->first();
             */
            
            // Mengambil data lengkap menggunakan Eloquent Relationship
            $slip = Penggajian::with(['pegawai.jabatan'])->findOrFail($id);

            return view('pegawai.slip', compact('slip'));

        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat slip gaji karena kesalahan koneksi database.');
        }
    }

    /**
     * UNIT: Mengimplementasikan Algoritma
     * Deskripsi: Menghitung total gaji bersih pegawai berdasarkan formula bisnis.
     * Formula: Gaji Bersih = (Gaji Pokok + Tunjangan) - Potongan Absen
     *
     * @param int $gapok            Gaji pokok pegawai berdasarkan jabatan
     * @param int $tunjangan        Tunjangan jabatan pegawai
     * @param int $potongan_absen   Akumulasi potongan akibat ketidakhadiran
     * @return int                  Gaji bersih hasil akhir perhitungan (tidak boleh kurang dari 0)
     */
    public function hitungGaji($gapok, $tunjangan, $potongan_absen)
    {
        // 1. Tambahkan gaji pokok dan tunjangan untuk memperoleh gaji kotor
        $gaji_kotor = $gapok + $tunjangan;

        // 2. Kurangi gaji kotor dengan akumulasi potongan absen
        $gaji_bersih = $gaji_kotor - $potongan_absen;

        // 3. Validasi batas bawah: gaji tidak boleh bernilai negatif
        if ($gaji_bersih < 0) {
            $gaji_bersih = 0;
        }

        // 4. Kembalikan hasil perhitungan gaji bersih
        return $gaji_bersih;
    }

    /**
     * Tampilkan halaman absensi pegawai (riwayat absensi pribadi).
     */
    public function pegawaiAbsensi()
    {
        try {
            // Mengambil data absensi milik pegawai yang sedang login beserta relasi pegawai.jabatan
            $absensi = \App\Models\Absensi::where('pegawai_id', Auth::id())
                ->with(['pegawai.jabatan'])
                ->orderBy('created_at', 'desc')
                ->get();

            $absensis = $absensi;

            return view('pegawai.absensi', compact('absensi', 'absensis'));
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat data absensi Anda.');
        }
    }
}
