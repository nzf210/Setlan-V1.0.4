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
            'id_keg' => $this->id_keg,
            'barang' => new BarangResource($this->whenLoaded('barang')),
            'unit' => new MUnitResource($this->whenLoaded('unit')),
            'opd' => new MOpdResource($this->whenLoaded('opd')),
            'kab' => new MKabResource($this->whenLoaded('kab')),
            'subkeg' => new MSubKegResource($this->whenLoaded('subkeg')),
            'jumlah' => $this->jumlah,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total_jumlah' => $this->total_jumlah,
            'akun' => new AkunAktifResource($this->whenLoaded('akun')),
        ];
    }
}
