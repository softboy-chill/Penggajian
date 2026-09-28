@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 680px; margin: 0 auto 2rem;">
    <div class="card-title">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
            Edit Data Pegawai
        </div>
        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.45rem 1rem;">
            ← Kembali
        </a>
    </div>

    <!-- Info Card Pegawai -->
    <div style="display: flex; align-items: center; gap: 14px; padding: 1rem 1.25rem; background: var(--primary-soft); border: 1px solid var(--primary-border); border-radius: var(--radius); margin-bottom: 1.75rem;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-light)); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">
            {{ strtoupper(substr($pegawai->name, 0, 1)) }}
        </div>
        <div>
            <div style="font-weight: 700; font-size: 1.05rem;">{{ $pegawai->name }}</div>
            <div style="color: var(--text-muted); font-size: 0.85rem;">{{ $pegawai->email }} &nbsp;·&nbsp; ID #{{ $pegawai->id }}</div>
        </div>
    </div>

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

    <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <!-- Nama Lengkap -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $pegawai->name) }}"
                    placeholder="Nama lengkap pegawai"
                    required>
            </div>

            <!-- Email -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="{{ old('email', $pegawai->email) }}"
                    placeholder="email@ptmajujaya.com"
                    required>
            </div>

            <!-- Password Baru -->
            <div class="form-group">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Kosongkan jika tidak diubah">
                <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 0.35rem;">Biarkan kosong jika tidak ingin mengganti password.</small>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    placeholder="Ulangi password baru">
            </div>

            <!-- Jabatan -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="jabatan_id" class="form-label">Jabatan</label>
                <select id="jabatan_id" name="jabatan_id" class="form-control" required>
                    <option value="">— Pilih Jabatan —</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}"
                            {{ old('jabatan_id', $pegawai->jabatan_id) == $jabatan->id ? 'selected' : '' }}>
                            {{ $jabatan->nama_jabatan }}
                            (Gapok: Rp {{ number_format($jabatan->gaji_pokok, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
            <button type="submit" class="btn" style="flex: 1;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
