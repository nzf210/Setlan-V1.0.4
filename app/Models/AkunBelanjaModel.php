<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunBelanjaModel extends Model
{
    use HasFactory;
    protected $table = 'akun_belanja';
    protected $primaryKey = 'id_akun';
    public $timestamps = false;
    protected $fillable = [
        'id_akun',
        'nama_akun',
        'tahun',
        'kode_akun'
    ];

    public function  scopeFiltered(Builder $quary)
    {
        if (request('nama_akun') == null) {
            return $quary;
        }
        $quary
            ->when(request('nama_akun'), function (Builder $q) {
                $q->where('nama_akun', 'like', '%' . request('nama_akun') . '%')
                ->orWhere('kode_akun', 'like', '%' . request('nama_akun') . '%');
            })
            ->when(request('sort_by'), function (Builder $q) {
                $sortBy = request('sort_by');
                $sortOrder = request('sort_order', 'asc');
                if (in_array($sortBy, ['nama_akun'])) {
                    $q->orderBy($sortBy, $sortOrder);
                }
            });
    }

}
