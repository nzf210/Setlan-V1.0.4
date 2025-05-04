<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitSubKegResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_kabupaten' => $this->id_kab,
            'id_opd' => $this->id_opd,
            'id_unit' => $this->id_unit,
            'id_subkeg' => $this->id_subkeg,
            'tahun' => $this->tahun,

            // Relasi
            'kabupaten' => new MKabResource($this->whenLoaded('kabupaten')),
            'opd' => new MOpdResource($this->whenLoaded('opd')),
            'unit' => new MUnitResource($this->whenLoaded('unit')),
            'sub_kegiatan' => new MSubKegResource($this->whenLoaded('subKegiatan')),
        ];
    }
}
