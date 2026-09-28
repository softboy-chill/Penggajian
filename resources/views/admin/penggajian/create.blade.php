@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-title">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
            Input Gaji Pegawai
        </div>
        <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.penggajian.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="pegawai_id" class="form-label">Pilih Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                <option value="" disabled selected>-- Pilih Pegawai --</option>
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->id }}" {{ old('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
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

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Bulan --</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                        <option value="{{ $bln }}" {{ old('bulan') == $bln ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
                @error('bulan')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-control" required>
                    @for($yr = 2024; $yr <= 2030; $yr++)
                        <option value="{{ $yr }}" {{ old('tahun', date('Y')) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endfor
                </select>
                @error('tahun')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn" style="width: 100%;">
                Simpan &amp; Hitung Gaji
            </button>
        </div>
    </form>
</div>
@endsection
