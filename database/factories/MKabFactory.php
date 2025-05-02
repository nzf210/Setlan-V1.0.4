<?php

namespace Database\Factories;

use App\Models\MKab;
use Illuminate\Database\Eloquent\Factories\Factory;

class MkabFactory extends Factory
{
    protected $model = MKab::class;

    protected static $index = 0;

    protected static $kabupaten = [
        ['id_kab' => '91.19', 'nama_kab' => 'Supiori'],
        ['id_kab' => '91.20', 'nama_kab' => 'Membramo Raya'],
        ['id_kab' => '01.01', 'nama_kab' => 'Kabupaten Tes']
    ];

    public function definition()
    {
        $kabupaten = self::$kabupaten[self::$index];
        self::$index = (self::$index + 1) % count(self::$kabupaten); // Cycle through the elements

        return [
            'id_kab' => $kabupaten['id_kab'],
            'nama_kab' => $kabupaten['nama_kab'],
        ];
    }

    protected static $akun6 = [
        
         ];

    protected static $akun5 = [
        
         ];

    protected static $akun4 = [
        
         ];

    protected static $akun3 = [
        
         ];

    protected static $akun2 = [
        
         ];

    protected static $akun1 = [
        
         ];
}
