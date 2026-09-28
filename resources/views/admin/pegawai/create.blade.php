@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 680px; margin: 0 auto 2rem;">
    <div class="card-title">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
            Tambah Pegawai Baru
        </div>
        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.45rem 1rem;">
            ← Kembali
        </a>
    </div>

    <p style="color: var(--text-muted); margin-bottom: 1.75rem; font-size: 0.95rem;">
        Isi formulir di bawah ini untuk menambahkan akun pegawai baru ke dalam sistem.
    </p>

    @if($errors->any())
        <div class="alert alert-error" style="flex-direction: column; align-items: flex-start;">
            <strong>Terdapat kesalahan input:</strong>
            <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pegawai.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <!-- Nama Lengkap -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Budi Santoso"
                    required>
            </div>

            <!-- Email -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="{{ old('email') }}"
                    placeholder="Contoh: budi@ptmajujaya.com"
                    required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Minimal 6 karakter"
                    required>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    placeholder="Ulangi password"
                    required>
            </div>

            <!-- Jabatan -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="jabatan_id" class="form-label">Jabatan</label>
                <select id="jabatan_id" name="jabatan_id" class="form-control" required>
                    <option value="">— Pilih Jabatan —</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}"
                            {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                            {{ $jabatan->nama_jabatan }}
                            (Gapok: Rp {{ number_format($jabatan->gaji_pokok, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @if($jabatans->isEmpty())
                    <p style="color: var(--danger-light); font-size: 0.8rem; margin-top: 0.4rem;">
                        ⚠ Belum ada data jabatan. Silakan tambah jabatan terlebih dahulu.
                    </p>
                @endif
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
            <button type="submit" class="btn" style="flex: 1;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Pegawai
            </button>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
