<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MKab extends Model
{

    use HasFactory;
    protected $table = 'm_kabs';
    protected $primaryKey = 'id_kab';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_kab',
        'nama_kab',
    ];



public function kodeBarangs()
{
    return $this->hasMany(KodeBarang::class, 'id_kab', 'id_kab');
}
}
