@extends('layouts.app')

@section('content')
<div class="card animate-fade-in">
    <div class="card-title" style="margin-bottom: 0.5rem;">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">PORTAL PEGAWAI</span>
            Dashboard Slip Gaji Anda
        </div>
    </div>
    
    <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 0.95rem;">
        Halo, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->jabatan->nama_jabatan ?? 'Tanpa Jabatan' }}). Di bawah ini adalah rincian slip gaji bulanan Anda. Klik tombol <strong>Lihat Slip</strong> untuk mencetak atau melihat detail.
    </p>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bulan / Tahun</th>
                    <th>Potongan Absen</th>
                    <th>Gaji Bersih</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penggajians as $key => $penggajian)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td style="font-weight: 600; color: var(--text-main);">{{ $penggajian->bulan }} {{ $penggajian->tahun }}</td>
                        <td style="color: var(--danger);">Rp {{ number_format($penggajian->potongan_absen, 0, ',', '.') }}</td>
                        <td style="font-weight: 700; color: var(--success);">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('pegawai.slip', $penggajian->id) }}" class="btn" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                                Lihat Slip
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="color: var(--text-muted); padding: 3rem 1rem;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 0.5rem; opacity: 0.5; display: block; margin: 0 auto 0.5rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Belum ada data slip gaji untuk akun Anda. Hubungi bagian admin jika ini kesalahan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
