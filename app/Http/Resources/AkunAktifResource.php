<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AkunAktifResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_akun_aktif' => $this->id_akun_aktif,
            'id_akun' => $this->akun->id_akun,
            'nama_akun_aktif' => $this->akun->nama_akun,
            'nama_akun' => $this->akun->nama_akun,
            'kode_akun_aktif' => $this->akun->kode_akun,
        ];
    }
}
