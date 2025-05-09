<?php

namespace Database\Factories;

use App\Models\KabupatenModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class KabupatenModelFactory extends Factory
{
    protected $model = KabupatenModel::class;
    protected static $index = 0;
    protected static $kabupaten = [
        ['id_kabupaten' => '91.19', 'nama_kabupaten' => 'Supiori'],
        ['id_kabupaten' => '91.20', 'nama_kabupaten' => 'Membramo Raya'],
        ['id_kabupaten' => '01.01', 'nama_kabupaten' => 'Kabupaten Tes']
    ];

    public function definition()
    {
        $kabupaten = self::$kabupaten[self::$index];
        self::$index = (self::$index + 1) % count(self::$kabupaten);

        return [
            'kode_kabupaten' => $kabupaten['id_kabupaten'],
            'nama_kabupaten' => $kabupaten['nama_kabupaten'],
        ];
    }
}
