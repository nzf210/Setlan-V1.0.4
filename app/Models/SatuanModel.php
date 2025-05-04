<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanModel extends Model
{
    use HasFactory;
    protected  $table = 'satuan';
    protected $primaryKey = 'id_satuan';
    protected $fillable = [
        'id_satuan',
        'nama_satuan',
    ];
    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(BarangModel::class);
    }

    public function  scopeFiltered(Builder $quary)
    {
        if (request('nama_satuan') == null) {
            return $quary;
        }
        $quary
            ->when(request('nama_satuan'), function (Builder $q) {
                $q->where('nama_satuan', 'like', '%' . request('nama_satuan') . '%');
            })
            ->when(request('sort_by'), function (Builder $q) {
                $sortBy = request('sort_by');
                $sortOrder = request('sort_order', 'asc');
                if (in_array($sortBy, ['nama_satuan'])) {
                    $q->orderBy($sortBy, $sortOrder);
                }
            });
    }
}
