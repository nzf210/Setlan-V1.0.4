<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MKeg extends Model
{
    use HasFactory;
    protected $table = 'm_kegs';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_keg',
        'tahun',
        'nama'
    ];

    public function subkegs()
    {
        return $this->hasMany(MSubkeg::class, 'id_keg', 'id_keg')
            ->whereColumn('m_subkegs.tahun', 'm_kegs.tahun');
    }
}
