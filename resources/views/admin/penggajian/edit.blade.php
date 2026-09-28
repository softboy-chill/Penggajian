@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-title">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
            Edit Data Penggajian
        </div>
        <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    {{-- Info ringkasan data lama --}}
    <div style="background: var(--primary-soft); border: 1px solid var(--primary-border); border-radius: var(--radius); padding: 1rem 1.25rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
        <span style="color: var(--text-muted); display: block; margin-bottom: 0.25rem; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Data Saat Ini</span>
        <strong>{{ $penggajian->pegawai->name }}</strong> &mdash;
        {{ $penggajian->bulan }} {{ $penggajian->tahun }} &mdash;
        <span style="color: var(--success); font-weight: 700;">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</span>
    </div>

    <form action="{{ route('admin.penggajian.update', $penggajian->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Pilih Pegawai --}}
        <div class="form-group">
            <label for="pegawai_id" class="form-label">Pilih Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                <option value="" disabled>-- Pilih Pegawai --</option>
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->id }}"
                        {{ old('pegawai_id', $penggajian->pegawai_id) == $pegawai->id ? 'selected' : '' }}>
                        {{ $pegawai->name }} - {{ $pegawai->jabatan->nama_jabatan ?? 'Tanpa Jabatan' }}
                        (Gaji pokok: Rp{{ number_format($pegawai->jabatan->gaji_pokok ?? 0, 0, ',', '.') }},
                        Tunjangan: Rp{{ number_format($pegawai->jabatan->tunjangan ?? 0, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
            @error('pegawai_id')
                <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
            @enderror
        </div>

        {{-- Bulan & Tahun --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-control" required>
                    <option value="" disabled>-- Pilih Bulan --</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                        <option value="{{ $bln }}" {{ old('bulan', $penggajian->bulan) == $bln ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
                @error('bulan')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-control" required>
                    @for($yr = 2020; $yr <= 2030; $yr++)
                        <option value="{{ $yr }}" {{ old('tahun', $penggajian->tahun) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endfor
                </select>
                @error('tahun')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Info potongan otomatis --}}
        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius); padding: 0.9rem 1.1rem; margin-bottom: 1.25rem; font-size: 0.85rem; color: #92400e;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle; margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Potongan absen dan gaji bersih akan <strong>dihitung ulang otomatis</strong> berdasarkan data absensi bulan &amp; tahun yang dipilih.
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn" style="flex: 1;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
