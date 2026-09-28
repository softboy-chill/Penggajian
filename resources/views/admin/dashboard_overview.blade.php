@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <!-- Header Welcome -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
            <h2 style="font-family: var(--font-display); font-size: 1.75rem; font-weight: 800; color: var(--text-main);">Dashboard </h2>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.penggajian.create') }}" class="btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Gaji Baru
            </a>
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">
                Input Absen
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Card 1: Total Pegawai -->
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid var(--primary);">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Pegawai</span>
                <span style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--text-main);">{{ $totalPegawai }} Orang</span>
            </div>
        </div>

        <!-- Card 2: Total Jabatan -->
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #f59e0b;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(245, 158, 11, 0.08); color: #f59e0b; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Jabatan</span>
                <span style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--text-main);">{{ $totalJabatan }} Jabatan</span>
            </div>
        </div>

        <!-- Card 3: Total Transaksi Gaji -->
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid var(--success);">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--success-glow-bg); color: var(--success); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Slip Gaji Dibuat</span>
                <span style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--text-main);">{{ $totalPenggajian }} Transaksi</span>
            </div>
        </div>

        <!-- Card 4: Total Pengeluaran Gaji -->
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid var(--danger);">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--danger-soft); color: var(--danger); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 11h4"></path></svg>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Payroll</span>
                <span style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--danger);">Rp {{ number_format($totalPengeluaranGaji, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem; margin-top: 1rem;">
        
        <!-- Recent Activities / Transaksi Gaji Terakhir -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-title" style="font-size: 1.2rem; margin-bottom: 1.25rem; font-family: var(--font-display); font-weight: 700;">
                Riwayat Payroll Terbaru
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Jabatan</th>
                            <th>Periode</th>
                            <th>Gaji Bersih</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPenggajians as $penggajian)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-main);">{{ $penggajian->pegawai->name }}</td>
                                <td>{{ $penggajian->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                                <td>{{ $penggajian->bulan }} {{ $penggajian->tahun }}</td>
                                <td style="font-weight: 700; color: var(--success);">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.slip', $penggajian->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                        Lihat Slip
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center" style="color: var(--text-muted); padding: 3rem 1rem;">
                                    Belum ada transaksi payroll.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 1.25rem; text-align: right;">
                <a href="{{ route('admin.penggajian.index') }}" style="color: var(--primary); font-weight: 600; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    Lihat Semua Riwayat Payroll &rarr;
                </a>
            </div>
        </div>

        <!-- Quick Access panel / Panel Pintas -->
        <div class="card" style="margin-bottom: 0; background: var(--bg-glass); display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card-title" style="font-size: 1.2rem; margin-bottom: 0.25rem; font-family: var(--font-display); font-weight: 700;">
                Akses Cepat
            </div>
            
            <p style="color: var(--text-muted); font-size: 0.85rem;">
                Kelola data operasional internal kantor PT Maju Jaya secara langsung.
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('admin.pegawai.create') }}" class="btn btn-secondary" style="justify-content: flex-start; padding: 0.8rem 1rem; width: 100%;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary); margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>Tambah Pegawai Baru</span>
                </a>
                
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary" style="justify-content: flex-start; padding: 0.8rem 1rem; width: 100%;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #f59e0b; margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span>Kelola Absensi</span>
                </a>

                <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary" style="justify-content: flex-start; padding: 0.8rem 1rem; width: 100%;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--success); margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Kelola Penggajian</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 1024px) {
        div[style*="display: grid"][style*="grid-template-columns: 1fr;"] {
            grid-template-columns: 1fr 350px !important;
        }
    }
</style>
@endsection
