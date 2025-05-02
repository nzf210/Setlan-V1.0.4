<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSubkeg extends Model
{
    use HasFactory;
    protected $table = 'm_subkegs';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_subkeg',
        'id_keg',
        'nama',
        'tahun'
    ];
    public function  scopeFiltered(Builder $quary)
    {
        if (request('nama') == null) {
            return $quary;
        }

        $quary
            ->when(request('nama'), function (Builder $q) {
                $q->where('nama', 'like', '%' . request('nama') . '%');
            })
            ->when(request('sort_by'), function (Builder $q) {
                $sortBy = request('sort_by');
                $sortOrder = request('sort_order', 'asc');
                if (in_array($sortBy, ['nama'])) {
                    $q->orderBy($sortBy, $sortOrder);
                }
            });
    }

    public function keg()
    {
        return $this->belongsTo(MKeg::class, 'id_keg', 'id_keg')
            ->whereColumn('m_subkegs.tahun', 'm_kegs.tahun');
    }

    public function kegs()
    {
        return $this->belongsTo(MKeg::class, 'id_keg', 'id_keg');
            // ->where('tahun', $this->tahun); // Jika perlu filter tahun
    }

}
