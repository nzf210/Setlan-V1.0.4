<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubKegiatanResource extends JsonResource
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
            'id_unit' => $this->id_unit,
            'id_opd' => $this->id_opd,
            'id_kabupaten' => $this->id_kab,
            'id_subkeg' => $this->id_subkeg,
            'nama_keg' => $this->subKegiatan?->kegs?->nama,
            'nama' => $this->subKegiatan?->nama,
            'tahun' => $this->tahun,
            'unit' => $this->unit?->nama_unit,
            'opd' => $this->opd?->nama_opd,
            'kabupaten' => $this->kabupaten?->nama_kab,
        ];
    }
}
