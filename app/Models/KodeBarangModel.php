<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeBarangModel extends Model
{
    use HasFactory;

    protected $table = 'kode_barang';
    protected $primaryKey = 'id_kode_barang';
    public $timestamps = false;

    protected $fillable = [
        'id_kode_barang',
        'id_kabupaten',
        'kode_barang',
        'nama_kode_barang',
        'tahun',
    ];

    public function barang()
    {
        return $this->belongsTo(BarangModel::class);
    }

    public function kabupaten(){
        return $this->belongsTo(KabupatenModel::class, 'id_kabupaten', 'id_kabupaten');
    }

}
