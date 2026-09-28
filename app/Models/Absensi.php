<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'bulan',
        'tahun',
        'hadir',
        'sakit',
        'izin',
        'alpa',
    ];

    /**
     * Get the pegawai (user) associated with the absensi.
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Accessor for total potongan.
     * Assuming Rp 100,000 deduction per day of alpa (unexcused absence).
     */
    public function getTotalPotonganAttribute()
    {
        return ($this->alpa ?? 0) * 100000;
    }

    /**
     * Accessor for estimated gaji bersih.
     * Gaji Bersih = (Gaji Pokok + Tunjangan) - Total Potongan
     */
    public function getGajiBersihAttribute()
    {
        if (!$this->pegawai) {
            return 0;
        }

        // Load relationship if not already loaded to get jabatan gaji_pokok/tunjangan
        $pegawai = $this->pegawai;
        $gapok = $pegawai->jabatan->gaji_pokok ?? 0;
        $tunjangan = $pegawai->jabatan->tunjangan ?? 0;

        $gaji_kotor = $gapok + $tunjangan;
        $gaji_bersih = $gaji_kotor - $this->total_potongan;

        return max(0, $gaji_bersih);
    }
}
