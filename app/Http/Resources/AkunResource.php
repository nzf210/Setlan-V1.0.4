<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AkunResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_akun' => $this->id_akun,
            'nama_akun' => $this->nama_akun,
            'kode_akun' => $this->kode_akun,
        ];
    }
}
