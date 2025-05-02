<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';
    protected $fillable = [
        'id_barang',
        'id_unit',
        'id_opd',
        'id_kab',
        'id_keg',
        'id_subkeg',
        'type',
        'tgl_beli',
        'tahun_buat',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function unit()
    {
        return $this->belongsTo(MUnit::class, 'id_unit', 'id_unit');
    }
    public function opd()
    {
        return $this->belongsTo(MOpd::class, 'id_opd', 'id_opd');
    }
    public function kab()
    {
        return $this->belongsTo(MKab::class, 'id_kab', 'id_kab');
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
    public function subkeg()
    {
        return $this->belongsTo(UnitSubKeg::class, 'id_subkeg', 'id');
    }
    public function  scopeFiltered(Builder $quary)
    {
        if (request('nama_r6') == null) {
            return $quary;
        }
        $quary
            ->when(request('nama_r6'), function (Builder $q) {
                $q->where('nama', 'like', '%' . request('nama_r6') . '%');
            })
            ->when(request('sort_by'), function (Builder $q) {
                $sortBy = request('sort_by');
                $sortOrder = request('sort_order', 'asc');
                if (in_array($sortBy, ['nama'])) {
                    $q->orderBy($sortBy, $sortOrder);
                }
            });
    }
}
