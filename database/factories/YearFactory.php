<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class YearFactory extends Factory
{
    protected static $year = [
        ['year' => 2023],
        ['year' => 2024 , 'akun' => 2024 , 'sub_keg' => 2024, 'kd_id_barang' => 2024, 'keg' => 2024],
        ['year' => 2025],
        ['year' => 2026],
    ];
    public function definition(): array
    {
        return [];
    }

    public function predefinedData(): array
    {
        return static::$year;
    }
}
