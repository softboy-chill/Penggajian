<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'bulan',
        'tahun',
        'potongan_absen',
        'total_gaji',
    ];

    /**
     * Get the pegawai that owns this salary slip.
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
