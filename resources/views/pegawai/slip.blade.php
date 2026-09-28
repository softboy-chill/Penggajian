@extends('layouts.app')

@section('content')
<style>
    .slip-card {
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 3rem;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    }
    .slip-header {
        border-bottom: 2px solid var(--border-glass);
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .slip-logo h1 {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 1.8rem;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, var(--primary-accent), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .slip-logo p {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    .slip-meta {
        text-align: right;
    }
    .slip-meta h3 {
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .slip-meta p {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    .slip-section-title {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--primary-accent);
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--border-glass);
        padding-bottom: 0.5rem;
    }
    .slip-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2.5rem;
    }
    .slip-info-list {
        list-style: none;
    }
    .slip-info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        font-size: 0.9rem;
    }
    .slip-info-item:last-child {
        border-bottom: none;
    }
    .slip-info-label {
        color: var(--text-muted);
    }
    .slip-info-value {
        font-weight: 600;
        color: var(--text-main);
    }
    .total-section {
        background: var(--primary-soft);
        border: 1px solid var(--primary-border);
        padding: 1.5rem;
        border-radius: var(--radius);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
    }
    .total-title {
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-accent);
    }
    .total-amount {
        font-family: var(--font-display);
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--success);
    }
    .signature-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        margin-top: 3rem;
    }
    .signature-box {
        text-align: center;
    }
    .signature-line {
        border-bottom: 1px solid var(--text-muted);
        width: 150px;
        margin: 4.5rem auto 0.5rem auto;
    }
    .signature-box p {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        .slip-card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
        .slip-info-value, .total-amount, .total-title, .slip-section-title {
            color: black !important;
        }
        .total-section {
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
        }
    }
</style>

<div class="no-print" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; max-width: 800px; margin-left: auto; margin-right: auto;">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Dashboard
    </a>
    <button onclick="window.print()" class="btn">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2zm5-12h.01M9 16h6"></path></svg>
        Cetak Slip Gaji
    </button>
</div>

<div class="slip-card">
    <div class="slip-header">
        <div class="slip-logo">
            <h1>PT MAJU JAYA</h1>
            <p>Jl. Jenderal Sudirman No. 45, Jakarta Pusat</p>
        </div>
        <div class="slip-meta">
            <h3>SLIP GAJI RESMI</h3>
            <p>ID Slip: #PAY-{{ str_pad($slip->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p>Periode: <strong>{{ $slip->bulan }} {{ $slip->tahun }}</strong></p>
        </div>
    </div>

    <!-- Informasi Pegawai -->
    <div class="slip-section-title">Informasi Pegawai</div>
    <div class="slip-grid" style="grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div class="slip-info-list" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="slip-info-item">
                <span class="slip-info-label">Nama Pegawai:</span>
                <span class="slip-info-value">{{ $slip->pegawai->name }}</span>
            </div>
            <div class="slip-info-item">
                <span class="slip-info-label">Jabatan:</span>
                <span class="slip-info-value">{{ $slip->pegawai->jabatan->nama_jabatan ?? 'Tanpa Jabatan' }}</span>
            </div>
            <div class="slip-info-item">
                <span class="slip-info-label">Email:</span>
                <span class="slip-info-value">{{ $slip->pegawai->email }}</span>
            </div>
            <div class="slip-info-item">
                <span class="slip-info-label">Status Akses:</span>
                <span class="slip-info-value" style="text-transform: uppercase;">{{ $slip->pegawai->role }}</span>
            </div>
        </div>
    </div>

    <!-- Rincian Gaji -->
    <div class="slip-grid">
        <!-- Penerimaan -->
        <div>
            <div class="slip-section-title">Penerimaan (Pendapatan)</div>
            <div class="slip-info-list">
                <div class="slip-info-item">
                    <span class="slip-info-label">Gaji Pokok:</span>
                    <span class="slip-info-value">Rp {{ number_format($slip->pegawai->jabatan->gaji_pokok ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="slip-info-item">
                    <span class="slip-info-label">Tunjangan Jabatan:</span>
                    <span class="slip-info-value">Rp {{ number_format($slip->pegawai->jabatan->tunjangan ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <!-- Potongan -->
        <div>
            <div class="slip-section-title">Potongan (Pengurangan)</div>
            <div class="slip-info-list">
                <div class="slip-info-item">
                    <span class="slip-info-label">Potongan Absen:</span>
                    <span class="slip-info-value" style="color: var(--danger);">Rp {{ number_format($slip->potongan_absen, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Gaji Bersih -->
    <div class="total-section">
        <div class="total-title">Total Gaji Bersih (Take Home Pay)</div>
        <div class="total-amount">Rp {{ number_format($slip->total_gaji, 0, ',', '.') }}</div>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-grid">
        <div class="signature-box">
            <p>Penerima Gaji,</p>
            <div class="signature-line"></div>
            <p style="font-weight: 600; color: var(--text-main);">{{ $slip->pegawai->name }}</p>
            <p>Pegawai</p>
        </div>
        <div class="signature-box">
            <p>Jakarta, {{ date('d F Y', strtotime($slip->created_at)) }}</p>
            <p>Finance & HRD,</p>
            <div class="signature-line"></div>
            <p style="font-weight: 600; color: var(--text-main);">Admin PT Maju Jaya</p>
            <p>Staff Keuangan</p>
        </div>
    </div>
</div>
@endsection
