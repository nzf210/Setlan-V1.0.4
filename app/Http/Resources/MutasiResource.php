<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MutasiResource extends JsonResource
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
            'id_kegiatan' => $this->id_kegiatan,
            'barang' => new BarangResource($this->whenLoaded('barang')),
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'opd' => new OpdResource($this->whenLoaded('opd')),
            'kabupaten' => new KabupatenResource($this->whenLoaded('kabupaten')),
            'sub_kegiatan' => new SubKegiatanResource($this->whenLoaded('sub_kegiatan')),
            'jumlah' => $this->jumlah,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total_jumlah' => $this->total_jumlah,
            'akun_aktif' => new AkunAktifResource($this->whenLoaded('akun_aktif')),
        ];
    }
}
