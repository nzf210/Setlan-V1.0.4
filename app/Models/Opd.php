<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    use HasFactory;
    protected $table = 'opd';
    protected $primaryKey = 'id_opd';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_opd',
        'id_kabupaten',
        'nama_opd'
    ];
}
