<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_unit' => $this->id_unit,
            'id_opd' => $this->id_opd,
            'nama_unit' => $this->nama_unit,
        ];
    }
}
