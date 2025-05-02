<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class TahunFactory extends Factory
{
    protected static $tahun = [
        ['tahun' => 2023],
        ['tahun' => 2024 , 'tahun_akun' => 2024 , 'tahun_sub_kegiatan' => 2024, 'tahun_kode_barang' => 2024, 'kegiatan' => 2024],
        ['tahun' => 2025],
        ['tahun' => 2026],
    ];
    public function definition(): array
    {
        return [];
    }

    public function predefinedData(): array
    {
        return static::$tahun;
    }
}
