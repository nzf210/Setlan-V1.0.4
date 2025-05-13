<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_barang' => $this->id_barang,
            'nama_barang' => $this->nama_barang,
            'merek' => $this->merek,
            'type' => $this->type,
            'tahun_buat' => $this->tahun_buat,
            'tahun_beli' => $this->tahun_beli,
            'harga' => $this->harga,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'kode_barang' =>  new CategoryResource($this->whenLoaded('kode_barang')),
            'satuan' =>  new SatuanResource($this->whenLoaded('satuan')),
            'akun' => new AkunAktifResource($this->whenLoaded('akun')),
        ];
    }
}
