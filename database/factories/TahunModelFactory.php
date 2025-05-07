<?php

namespace Database\Factories;

use App\Models\TahunModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TahunModelFactory extends Factory
{
    protected static $index = 0;
    protected static $tahun = [
        [ 'kab'=> 1, 'tahun' => 2023],
        [ 'kab'=> 2, 'tahun' => 2024 , 'tahun_akun' => 2024 , 'tahun_sub_kegiatan' => 2024, 'tahun_kode_barang' => 2024, 'kegiatan' => 2024],
        [ 'kab'=> 3, 'tahun' => 2025],
        [ 'kab'=> 1, 'tahun' => 2026],
    ];
    protected $model = TahunModel::class;
    public function definition(): array
    {
        $tahun = self::$tahun[self::$index];
        self::$index = (self::$index + 1) % count(self::$tahun);
        return [
            'id_kabupaten' => $tahun['kab'],
            'tahun' => $tahun['tahun'],
            'tahun_akun' => $tahun['tahun_akun'] ?? $tahun['tahun'],
            'tahun_sub_kegiatan' => $tahun['tahun_sub_kegiatan'] ?? $tahun['tahun'],
            'tahun_kode_barang' => $tahun['tahun_kode_barang'] ?? $tahun['tahun'],
            'tahun_kegiatan' => $tahun['kegiatan'] ?? $tahun['tahun'],
        ];
    }
}
