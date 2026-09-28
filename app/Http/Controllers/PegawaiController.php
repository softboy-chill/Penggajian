<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rules\Password;

class PegawaiController extends Controller
{
    /**
     * Tampilkan daftar seluruh pegawai.
     */
    public function index()
    {
        try {
            $pegawais = Pegawai::where('role', 'pegawai')
                ->with('jabatan')
                ->orderBy('name', 'asc')
                ->get();

            return view('admin.pegawai.index', compact('pegawais'));
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat data pegawai. Kode Error: ' . $e->getCode());
        }
    }

    /**
     * Tampilkan form tambah pegawai baru.
     */
    public function create()
    {
        try {
            $jabatans = Jabatan::orderBy('nama_jabatan', 'asc')->get();
            return view('admin.pegawai.create', compact('jabatans'));
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat form. Gangguan koneksi database.');
        }
    }

    /**
     * Simpan data pegawai baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:pegawais,email',
            'password'   => ['required', 'confirmed', Password::min(6)],
            'jabatan_id' => 'required|exists:jabatans,id',
        ], [
            'name.required'       => 'Nama pegawai wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.unique'        => 'Email ini sudah digunakan oleh pegawai lain.',
            'password.required'   => 'Password wajib diisi.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'password.min'        => 'Password minimal 6 karakter.',
            'jabatan_id.required' => 'Jabatan wajib dipilih.',
            'jabatan_id.exists'   => 'Jabatan yang dipilih tidak valid.',
        ]);

        try {
            Pegawai::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'pegawai',
                'jabatan_id' => $request->jabatan_id,
            ]);

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai "' . $request->name . '" berhasil ditambahkan.');
        } catch (QueryException $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan data pegawai. Silakan coba lagi. Error: ' . $e->getCode());
        }
    }

    /**
     * Tampilkan form edit data pegawai.
     */
    public function edit(Pegawai $pegawai)
    {
        // Pastikan yang diedit hanya user dengan role pegawai
        if ($pegawai->role !== 'pegawai') {
            abort(403, 'Anda tidak memiliki izin untuk mengedit akun ini.');
        }

        try {
            $jabatans = Jabatan::orderBy('nama_jabatan', 'asc')->get();
            return view('admin.pegawai.edit', compact('pegawai', 'jabatans'));
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal memuat form edit. Gangguan koneksi database.');
        }
    }

    /**
     * Update data pegawai di database.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        if ($pegawai->role !== 'pegawai') {
            abort(403, 'Anda tidak memiliki izin untuk mengubah akun ini.');
        }

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:pegawais,email,' . $pegawai->id,
            'password'   => ['nullable', 'confirmed', Password::min(6)],
            'jabatan_id' => 'required|exists:jabatans,id',
        ], [
            'name.required'       => 'Nama pegawai wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.unique'        => 'Email ini sudah digunakan oleh pegawai lain.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'jabatan_id.required' => 'Jabatan wajib dipilih.',
        ]);

        try {
            $data = [
                'name'       => $request->name,
                'email'      => $request->email,
                'jabatan_id' => $request->jabatan_id,
            ];

            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $pegawai->update($data);

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Data pegawai "' . $pegawai->name . '" berhasil diperbarui.');
        } catch (QueryException $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui data pegawai. Error: ' . $e->getCode());
        }
    }

    /**
     * Hapus data pegawai dari database.
     */
    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->role !== 'pegawai') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus akun ini.');
        }

        try {
            $namaHapus = $pegawai->name;
            $pegawai->delete();

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai "' . $namaHapus . '" berhasil dihapus dari sistem.');
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal menghapus data pegawai. Pegawai mungkin masih memiliki riwayat penggajian. Error: ' . $e->getCode());
        }
    }
}
