<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatanModel extends Model
{
    use HasFactory;
    protected $table = 'sub_kegiatan';
    protected $primaryKey = 'id_sub_kegiatan';
    public $timestamps = false;
    protected $fillable = [
        'id_subkegiatan',
        'id_kegiatan',
        'kode_subkegiatan',
        'nama_sub_kegiatan',
        'tahun'
    ];

    public function  scopeFiltered(Builder $quary)
    {
        if (request('nama_sub_kegiatan') == null) {
            return $quary;
        }

        $quary
            ->when(request('nama_sub_kegiatan'), function (Builder $q) {
                $q->where('nama_sub_kegiatan', 'like', '%' . request('nama_sub_kegiatan') . '%');
            })
            ->when(request('sort_by'), function (Builder $q) {
                $sortBy = request('sort_by');
                $sortOrder = request('sort_order', 'asc');
                if (in_array($sortBy, ['nama_sub_kegiatan'])) {
                    $q->orderBy($sortBy, $sortOrder);
                }
            });
    }

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }

}
