<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubKegiatanAktifResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_sub_kegiatan_aktif' => $this->id_sub_kegiatan_aktif,
            'id_kabupaten' => $this->kabupaten->id_kabupaten,
            'nama_kabupaten' => $this->kabupaten?->nama_kabupaten,
            'id_opd' => $this->opd->id_opd,
            'nama_opd' => $this->opd?->nama_opd,
            'id_unit' => $this->unit->id_unit,
            'nama_unit' => $this->unit?->nama_unit,
            'id_sub_kegiatan' => $this->sub_kegiatan?->id_sub_kegiatan,
            'kode_sub_kegiatan' => $this->sub_kegiatan?->kode_sub_kegiatan,
            'nama_sub_kegiatan' => $this->sub_kegiatan?->nama_sub_kegiatan,
            'kode_kegiatan' => $this->sub_Kegiatan?->kegiatan->kode_kegiatan,
            'nama_kegiatan' => $this->sub_Kegiatan?->kegiatan->nama_kegiatan,
            'nomor_urut' => $this->nomor_urut,
            'tahun' => $this->tahun,
        ];
    }
}
