<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    use HasFactory;
    protected $table = 'unit';
    protected $primaryKey = 'id_unit';
    public $timestamps = false;
    protected $fillable = [
        'id_unit',
        'id_opd',
        'nama_unit',
    ];
}
