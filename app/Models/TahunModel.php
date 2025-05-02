<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunModel extends Model
{
    protected $table = "tahun";
    public $timestamps = false;
    protected $fillable = [
        'tahun',
        'tahun_akun',
        'tahun_kegiatan',
        'tahun_sub_kegiatan',
        'tahun_kode_barang'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tahun_akun' => 'integer',
        'tahun_kegiatan' => 'integer',
        'tahun_sub_kegiatan' => 'integer',
        'tahun_kode_barang' => 'integer'
    ];
}
