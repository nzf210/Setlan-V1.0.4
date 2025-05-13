<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_kode_barang' => $this->id_kode_barang,
            'nama_kode_barang' => $this->nama_kode_barang,
            'kode_barang' => $this->kode_barang,
        ];
    }
}
