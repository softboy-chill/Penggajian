@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-title">
        <div>
            <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
            Manajemen Data Pegawai
        </div>
        <a href="{{ route('admin.pegawai.create') }}" class="btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pegawai
        </a>
    </div>

    <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">
        Daftar seluruh pegawai yang terdaftar di sistem. Admin dapat menambah, mengedit, atau menghapus data pegawai.
    </p>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Email</th>
                    <th>Jabatan</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawais as $key => $pegawai)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-light)); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                                </div>
                                <span style="font-weight: 600; color: var(--text-main);">{{ $pegawai->name }}</span>
                            </div>
                        </td>
                        <td style="color: var(--text-muted);">{{ $pegawai->email }}</td>
                        <td>
                            @if($pegawai->jabatan)
                                <span class="badge badge-pegawai">{{ $pegawai->jabatan->nama_jabatan }}</span>
                            @else
                                <span class="badge" style="background: var(--danger-soft); color: var(--danger-light);">Belum Diset</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($pegawai->jabatan->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($pegawai->jabatan->tunjangan ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Hapus pegawai \"{{ $pegawai->name }}\"? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="color: var(--text-muted); padding: 3rem 1rem;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 0.5rem; opacity: 0.5; display: block; margin: 0 auto 0.5rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Belum ada data pegawai. Silakan tambah pegawai baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary footer -->
    @if($pegawais->count() > 0)
    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center;">
        <span style="color: var(--text-muted); font-size: 0.85rem;">
            Total <strong style="color: var(--text-main);">{{ $pegawais->count() }}</strong> pegawai terdaftar
        </span>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 1rem;">
            ← Kembali ke Dashboard
        </a>
    </div>
    @endif
</div>
@endsection
