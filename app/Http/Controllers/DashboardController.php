<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Absensi;
use App\Models\Penggajian;

class DashboardController extends Controller
{
    /**
     * Redirect user ke dashboard masing-masing sesuai role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('pegawai.dashboard');
    }

    /**
     * Tampilkan Halaman Utama Dashboard Admin.
     */
    public function adminDashboard()
    {
        $totalPegawai = Pegawai::where('role', 'pegawai')->count();
        $totalJabatan = Jabatan::count();
        $totalAbsensi = Absensi::count();
        $totalPenggajian = Penggajian::count();
        $totalPengeluaranGaji = Penggajian::sum('total_gaji');

        // Mengambil 5 rekap penggajian terbaru
        $recentPenggajians = Penggajian::with(['pegawai.jabatan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard_overview', compact(
            'totalPegawai',
            'totalJabatan',
            'totalAbsensi',
            'totalPenggajian',
            'totalPengeluaranGaji',
            'recentPenggajians'
        ));
    }
}
