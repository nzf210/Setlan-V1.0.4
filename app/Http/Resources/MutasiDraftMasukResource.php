<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MutasiDraftMasukResource extends JsonResource
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
            'id_barang' => $this->id_barang,
            'id_subkeg' => $this->id_subkeg,
            'id_kabupaten' => $this->id_kab,
            'id_opd' => $this->id_opd,
            'id_unit' => $this->id_unit,
            'jumlah' => $this->jumlah,
            'pajak' => $this->pajak,
            'penyesuaian' => $this->penyesuaian,
            'harga' => $this->barang->harga,
            'tot_harga' => $this->tot_harga,
            'type' => $this->type,
            'tgl_beli' => $this->tgl_beli,
            'tgl_expired' => $this->tgl_expired,
            'tahun_buat' => $this->tahun_buat,
            'tahun' => $this->tahun,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relasi
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'opd' => new OpdResource($this->whenLoaded('opd')),
            'kabupaten' => new KabupatenResource($this->whenLoaded('kabupaten')),
            'barang' => new BarangResource($this->whenLoaded('barang')),
            'sub_kegiatan' => new SubKegiatanResource($this->whenLoaded('sub_kegiatan')),
        ];
    }
}
