<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KabupatenModel extends Model
{

    use HasFactory;
    protected $table = 'kabupaten';
    protected $primaryKey = 'id_kabupaten';
    public $timestamps = false;

    protected $fillable = [
        'id_kabupaten',
        'nama_kabupaten',
    ];



public function kode_barang()
{
    return $this->hasMany(KodeBarangModel::class, 'id_kabupaten', 'id_kabupaten');
}
}
