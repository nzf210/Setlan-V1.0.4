<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraModel extends Model
{
    use HasFactory;

    protected $table = 'berita_acara';
    protected $primaryKey = 'id_berita_acara';
    protected $fillable = [
        'id_berita_acara',
        'id_barang',
        'no_ba',
        'tgl_ba',
        'text1',
        'text2',
        'text3',
        'text4',
        'ttd1',
        'ttd2',
        'ttd3',
        'ttd4',
        'type_ba',
        'image',
        'id_kabupaten',
        'id_opd',
        'id_unit',
        'tahun',
    ];
}
