@extends('layouts.app')

@section('content')
<div style="display: grid; grid-template-columns: 1fr; gap: 2rem; align-items: start; margin-bottom: 2rem; @media (min-width: 1024px) { grid-template-columns: 320px 1fr; }">
    <!-- Grid Wrapper for Responsive Desktop Layout -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Card Form Input -->
        <div class="card" id="form-card">
            <div class="card-title" id="form-title" style="font-size: 1.25rem; margin-bottom: 1rem;">
                Input Absensi Baru
            </div>
            
            <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.85rem;">
                Masukkan data rekap absensi pegawai bulanan di bawah ini.
            </p>

            <form action="{{ route('admin.absensi.store') }}" method="POST" id="absensi-form">
                @csrf
                <div id="method-container"></div>

                <!-- Dropdown Pegawai -->
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="pegawai_id" class="form-label">Pegawai</label>
                    <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}">
                                {{ $pegawai->name }} ({{ $pegawai->jabatan->nama_jabatan ?? 'Tanpa Jabatan' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Baris Bulan & Tahun Berdampingan (Rapi & Sejajar) -->
                <div style="display: flex; gap: 1rem; margin-bottom: 1.25rem; width: 100%;">
                    <!-- Dropdown Bulan -->
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control" style="width: 100%;" required>
                            <option value="" disabled selected>-- Bulan --</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                                <option value="{{ $bln }}" {{ old('bulan') == $bln ? 'selected' : '' }}>{{ $bln }}</option>
                            @endforeach
                        </select>
                        @error('bulan')
                            <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Dropdown Tahun -->
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control" style="width: 100%;" required>
                            <option value="" disabled selected>-- Tahun --</option>
                            @for($yr = 2024; $yr <= 2030; $yr++)
                                <option value="{{ $yr }}" {{ old('tahun', date('Y')) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endfor
                        </select>
                        @error('tahun')
                            <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Input Hadir -->
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="hadir" class="form-label">Hadir (Hari)</label>
                    <input type="number" name="hadir" id="hadir" class="form-control" min="0" value="0" required>
                </div>

                <!-- Input Sakit -->
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="sakit" class="form-label">Sakit (Hari)</label>
                    <input type="number" name="sakit" id="sakit" class="form-control" min="0" value="0" required>
                </div>

                <!-- Input Izin -->
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="izin" class="form-label">Izin (Hari)</label>
                    <input type="number" name="izin" id="izin" class="form-control" min="0" value="0" required>
                </div>

                <!-- Input Alpa -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="alpa" class="form-label">Alpa (Hari)</label>
                    <input type="number" name="alpa" id="alpa" class="form-control" min="0" value="0" required>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn" id="submit-btn" style="width: 100%;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Absensi
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancel-btn" style="width: 100%; display: none;" onclick="resetForm()">
                        Batal Edit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Rekap Absensi -->
    <div class="card">
        <div class="card-title">
            <div>
                <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN PANEL</span>
                Rekap Absensi Pegawai
            </div>
        </div>

        <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">
            Daftar rekap absensi pegawai PT Maju Jaya. Anda dapat menambah rekap baru menggunakan formulir di samping, mengedit, atau menghapusnya.
        </p>

        <div class="table-responsive">
            <table class="table">
                 <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Bulan / Tahun</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $key => $absensi)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td style="font-weight: 600; color: var(--text-main);">
                                {{ $absensi->pegawai->name ?? 'Pegawai Terhapus' }}
                            </td>
                            <td>{{ $absensi->bulan ?? '-' }} {{ $absensi->tahun ?? '-' }}</td>
                            <td><span style="font-weight: 600; color: var(--success);">{{ $absensi->hadir }} Hari</span></td>
                            <td><span style="color: var(--text-muted);">{{ $absensi->sakit }} Hari</span></td>
                            <td><span style="color: var(--text-muted);">{{ $absensi->izin }} Hari</span></td>
                            <td><span style="font-weight: 600; color: var(--danger);">{{ $absensi->alpa }} Hari</span></td>
                            <td class="text-center">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <button class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;" 
                                        onclick="editAbsensi({{ json_encode($absensi) }})">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.absensi.destroy', $absensi->id) }}" method="POST" onsubmit="return confirm('Hapus rekap absensi ini?')">
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
                            <td colspan="8" class="text-center" style="color: var(--text-muted); padding: 3rem 1rem;">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 0.5rem; opacity: 0.5; display: block; margin: 0 auto 0.5rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Belum ada data absensi yang dimasukkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- responsive desktop layout helper style -->
<style>
    @media (min-width: 1024px) {
        div[style*="display: grid"] {
            grid-template-columns: 340px 1fr !important;
        }
    }
</style>

<script>
    function editAbsensi(absensi) {
        // Ganti judul form & tombol submit
        document.getElementById('form-title').innerText = 'Edit Rekap Absensi';
        document.getElementById('submit-btn').innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> Perbarui Absensi';
        document.getElementById('cancel-btn').style.display = 'block';

        // Set action form ke update route
        const form = document.getElementById('absensi-form');
        form.action = `/admin/absensi/${absensi.id}`;

        // Tambah input method PUT
        document.getElementById('method-container').innerHTML = '@method("PUT")';

        // Isi form dengan data absensi terpilih
        document.getElementById('pegawai_id').value = absensi.pegawai_id;
        document.getElementById('bulan').value = absensi.bulan || '';
        document.getElementById('tahun').value = absensi.tahun || '';
        document.getElementById('hadir').value = absensi.hadir;
        document.getElementById('sakit').value = absensi.sakit;
        document.getElementById('izin').value = absensi.izin;
        document.getElementById('alpa').value = absensi.alpa;

        // Scroll halus ke form
        document.getElementById('form-card').scrollIntoView({ behavior: 'smooth' });
    }

    function resetForm() {
        // Ganti kembali judul form & tombol submit
        document.getElementById('form-title').innerText = 'Input Absensi Baru';
        document.getElementById('submit-btn').innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Absensi';
        document.getElementById('cancel-btn').style.display = 'none';

        // Reset action form ke store route
        const form = document.getElementById('absensi-form');
        form.action = "{{ route('admin.absensi.store') }}";

        // Hapus input method PUT
        document.getElementById('method-container').innerHTML = '';

        // Reset semua field
        form.reset();
        document.getElementById('pegawai_id').value = '';
        document.getElementById('bulan').value = '';
        document.getElementById('tahun').value = '';
        document.getElementById('hadir').value = 0;
        document.getElementById('sakit').value = 0;
        document.getElementById('izin').value = 0;
        document.getElementById('alpa').value = 0;
    }
</script>
@endsection