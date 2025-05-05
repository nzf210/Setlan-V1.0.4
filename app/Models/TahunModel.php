<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TahunModel extends Model
{
    use HasFactory;
    protected $table = "tahun";
    protected $primaryKey = "id_tahun";
    public $timestamps = false;
    protected $fillable = [
        'id_kabupaten',
        'tahun',
        'tahun_akun',
        'tahun_kegiatan',
        'tahun_sub_kegiatan',
        'tahun_kode_barang',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tahun_akun' => 'integer',
        'tahun_kegiatan' => 'integer',
        'tahun_sub_kegiatan' => 'integer',
        'tahun_kode_barang' => 'integer',
        'id_kabupaten' => 'integer'
    ];
}
