<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitSubKegResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_kabupaten' => $this->id_kabupaten,
            'id_opd' => $this->id_opd,
            'id_unit' => $this->id_unit,
            'id_subkeg' => $this->id_subkeg,
            'tahun' => $this->tahun,

            // Relasi
            'kabupaten' => new KabupatenResource($this->whenLoaded('kabupaten')),
            'opd' => new OpdResource($this->whenLoaded('opd')),
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'sub_kegiatan' => new SubKegiatanResource($this->whenLoaded('subKegiatan')),
        ];
    }
}
