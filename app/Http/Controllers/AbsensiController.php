<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Absensi;
use Illuminate\Database\QueryException;

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman absensi (daftar rekap absensi & form input).
     */
    public function index()
    {
        try {
            // Mengambil semua user yang berperan sebagai pegawai untuk dropdown pilihan
            $pegawais = Pegawai::where('role', 'pegawai')->orderBy('name', 'asc')->get();

            // Mengambil seluruh rekap absensi dengan Eager Loading relasi pegawai
            $absensis = Absensi::with('pegawai')->orderBy('created_at', 'desc')->get();

            return view('admin.absensi', compact('pegawais', 'absensis'));
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat data absensi. Error: ' . $e->getCode());
        }
    }

    /**
     * Simpan data absensi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan'      => 'required|string',
            'tahun'      => 'required|integer|min:2024|max:2030',
            'hadir'      => 'required|integer|min:0',
            'sakit'      => 'required|integer|min:0',
            'izin'       => 'required|integer|min:0',
            'alpa'       => 'required|integer|min:0',
        ], [
            'pegawai_id.required' => 'Pegawai harus dipilih.',
            'pegawai_id.exists'   => 'Pegawai yang dipilih tidak valid.',
            'bulan.required'      => 'Bulan rekap wajib diisi.',
            'tahun.required'      => 'Tahun rekap wajib diisi.',
            'hadir.required'      => 'Jumlah hadir wajib diisi.',
            'hadir.integer'       => 'Jumlah hadir harus berupa angka.',
            'hadir.min'           => 'Jumlah hadir minimal 0.',
            'sakit.required'      => 'Jumlah sakit wajib diisi.',
            'sakit.integer'       => 'Jumlah sakit harus berupa angka.',
            'sakit.min'           => 'Jumlah sakit minimal 0.',
            'izin.required'       => 'Jumlah izin wajib diisi.',
            'izin.integer'        => 'Jumlah izin harus berupa angka.',
            'izin.min'            => 'Jumlah izin minimal 0.',
            'alpa.required'       => 'Jumlah alpa wajib diisi.',
            'alpa.integer'        => 'Jumlah alpa harus berupa angka.',
            'alpa.min'            => 'Jumlah alpa minimal 0.',
        ]);

        try {
            Absensi::create([
                'pegawai_id' => $request->pegawai_id,
                'bulan'      => $request->bulan,
                'tahun'      => $request->tahun,
                'hadir'      => $request->hadir,
                'sakit'      => $request->sakit,
                'izin'       => $request->izin,
                'alpa'       => $request->alpa,
            ]);

            return redirect()->route('admin.absensi.index')
                ->with('success', 'Data absensi berhasil ditambahkan!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan data absensi. Error: ' . $e->getCode());
        }
    }

    /**
     * Update data absensi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan'      => 'required|string',
            'tahun'      => 'required|integer|min:2024|max:2030',
            'hadir'      => 'required|integer|min:0',
            'sakit'      => 'required|integer|min:0',
            'izin'       => 'required|integer|min:0',
            'alpa'       => 'required|integer|min:0',
        ], [
            'pegawai_id.required' => 'Pegawai harus dipilih.',
            'pegawai_id.exists'   => 'Pegawai yang dipilih tidak valid.',
            'bulan.required'      => 'Bulan rekap wajib diisi.',
            'tahun.required'      => 'Tahun rekap wajib diisi.',
            'hadir.required'      => 'Jumlah hadir wajib diisi.',
            'hadir.integer'       => 'Jumlah hadir harus berupa angka.',
            'hadir.min'           => 'Jumlah hadir minimal 0.',
            'sakit.required'      => 'Jumlah sakit wajib diisi.',
            'sakit.integer'       => 'Jumlah sakit harus berupa angka.',
            'sakit.min'           => 'Jumlah sakit minimal 0.',
            'izin.required'       => 'Jumlah izin wajib diisi.',
            'izin.integer'        => 'Jumlah izin harus berupa angka.',
            'izin.min'            => 'Jumlah izin minimal 0.',
            'alpa.required'       => 'Jumlah alpa wajib diisi.',
            'alpa.integer'        => 'Jumlah alpa harus berupa angka.',
            'alpa.min'            => 'Jumlah alpa minimal 0.',
        ]);

        try {
            $absensi = Absensi::findOrFail($id);
            $absensi->update([
                'pegawai_id' => $request->pegawai_id,
                'bulan'      => $request->bulan,
                'tahun'      => $request->tahun,
                'hadir'      => $request->hadir,
                'sakit'      => $request->sakit,
                'izin'       => $request->izin,
                'alpa'       => $request->alpa,
            ]);

            return redirect()->route('admin.absensi.index')
                ->with('success', 'Data absensi berhasil diperbarui!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data absensi. Error: ' . $e->getCode());
        }
    }

    /**
     * Hapus data absensi.
     */
    public function destroy($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            $absensi->delete();

            return redirect()->route('admin.absensi.index')
                ->with('success', 'Data absensi berhasil dihapus!');
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal menghapus data absensi. Error: ' . $e->getCode());
        }
    }

    /**
     * Ambil jumlah potongan secara dinamis berdasarkan user, bulan, dan tahun.
     */
    public function getPotongan(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan'      => 'required|string',
            'tahun'      => 'required|integer',
        ]);

        // Cari data absensi pegawai untuk bulan dan tahun ini
        $absensi = Absensi::where('pegawai_id', $request->pegawai_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();

        return response()->json([
            'potongan'   => $absensi ? $absensi->total_potongan : 0,
            'alpa'       => $absensi ? $absensi->alpa : 0,
            'has_record' => $absensi !== null,
        ]);
    }
}
