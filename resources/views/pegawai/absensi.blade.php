@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    @php
        $totalHadir = $absensi->sum('hadir');
        $totalSakit = $absensi->sum('sakit');
        $totalIzin = $absensi->sum('izin');
        $totalAlpa = $absensi->sum('alpa');
        $totalPotongan = $absensi->sum(function($item) {
            return $item->total_potongan;
        });
    @endphp

    <!-- Summary Card Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Card Total Hadir -->
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Kehadiran</span>
                <span style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--text-main);">{{ $totalHadir }} Hari</span>
            </div>
        </div>

        <!-- Card Total Alpa -->
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--danger-soft); color: var(--danger); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Ketidakhadiran (Alpa)</span>
                <span style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--danger);">{{ $totalAlpa }} Hari</span>
            </div>
        </div>

        <!-- Card Total Potongan -->
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--danger-soft); color: var(--danger); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M10 11h4"></path></svg>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Akumulasi Potongan</span>
                <span style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--danger);">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Card Tabel Utama -->
    <div class="card">
        <div class="card-title">
            <div>
                <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">PORTAL PEGAWAI</span>
                Data Kehadiran & Potongan Gaji Saya
            </div>
        </div>

        <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">
            Berikut adalah riwayat detail kehadiran Anda beserta estimasi pemotongan gaji akibat alpa. Hubungi bagian finance/admin jika terdapat kesalahan pencatatan.
        </p>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bulan / Tahun</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th>Total Potongan Absensi</th>
                        <th>Estimasi Gaji Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td style="font-weight: 600; color: var(--text-main);">
                                {{ $data->bulan ?? '-' }} {{ $data->tahun ?? '-' }}
                            </td>
                            <td>
                                <span class="badge badge-pegawai">{{ $data->hadir }} Hari</span>
                            </td>
                            <td style="color: var(--text-muted);">{{ $data->sakit }} Hari</td>
                            <td style="color: var(--text-muted);">{{ $data->izin }} Hari</td>
                            <td>
                                @if($data->alpa > 0)
                                    <span style="font-weight: 700; color: var(--danger);">{{ $data->alpa }} Hari</span>
                                @else
                                    <span style="color: var(--text-muted);">{{ $data->alpa }} Hari</span>
                                @endif
                            </td>
                            <td style="font-weight: 600; color: var(--danger);">
                                Rp {{ number_format($data->total_potongan, 0, ',', '.') }}
                            </td>
                            <td style="font-weight: 700; color: var(--success);">
                                Rp {{ number_format($data->gaji_bersih, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="color: var(--text-muted); padding: 4rem 1rem;">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 0.75rem; opacity: 0.5; display: block; margin: 0 auto 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Data absensi Anda belum diinput oleh Admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
