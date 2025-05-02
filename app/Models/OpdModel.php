<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdModel extends Model
{
    use HasFactory;
    protected $table = 'opd';
    protected $primaryKey = 'id_opd';
    public $timestamps = false;

    protected $fillable = [
        'id_opd',
        'id_kabupaten',
        'nama_opd'
    ];
}
