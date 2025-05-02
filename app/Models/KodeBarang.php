<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeBarang extends Model
{
    use HasFactory;

    protected $table = 'kode_barang';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_kab',
        'id_kd_barang',
        'tahun',
        'nama',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function kab(){
        return $this->belongsTo(MKab::class, 'id_kab', 'id_kab');
    }

}
