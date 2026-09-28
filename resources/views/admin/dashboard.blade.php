@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-title">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
            Kelola Penggajian Pegawai
        </div>
        {{-- Tombol Input Gaji Baru TETAP ADA --}}
        <a href="{{ route('admin.penggajian.create') }}" class="btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Input Gaji Baru
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: var(--radius); padding: 0.85rem 1.1rem; margin-bottom: 1.25rem; color: #065f46; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: var(--radius); padding: 0.85rem 1.1rem; margin-bottom: 1.25rem; color: #991b1b; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">
        Berikut adalah daftar riwayat penggajian seluruh pegawai PT Maju Jaya. Anda dapat melakukan input penggajian bulanan baru serta mencetak slip gaji masing-masing pegawai.
    </p>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Jabatan</th>
                    <th>Bulan / Tahun</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th>Potongan Absen</th>
                    <th>Gaji Bersih</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penggajians as $key => $penggajian)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td style="font-weight: 600; color: var(--text-main);">{{ $penggajian->pegawai->name }}</td>
                        <td>{{ $penggajian->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                        <td>{{ $penggajian->bulan }} {{ $penggajian->tahun }}</td>
                        <td>Rp {{ number_format($penggajian->pegawai->jabatan->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($penggajian->pegawai->jabatan->tunjangan ?? 0, 0, ',', '.') }}</td>
                        <td style="color: var(--danger);">Rp {{ number_format($penggajian->potongan_absen, 0, ',', '.') }}</td>
                        <td style="font-weight: 700; color: var(--success);">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <div style="display: flex; gap: 0.4rem; justify-content: center; flex-wrap: wrap;">
                                {{-- Tombol Lihat Slip TETAP ADA --}}
                                <a href="{{ route('admin.slip', $penggajian->id) }}"
                                   class="btn btn-secondary"
                                   style="padding: 0.4rem 0.7rem; font-size: 0.78rem;"
                                   title="Lihat Slip">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Slip
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="color: var(--text-muted); padding: 3rem 1rem;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 0.5rem; opacity: 0.5; display: block; margin: 0 auto 0.5rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Belum ada riwayat penggajian yang disimpan. Silakan buat penggajian baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection