<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Satuan>
 */
class SatuanFactory extends Factory
{
    protected static $elements = [
        [
            'nama' => 'Meter',
            'description' => 'Satuan panjang'
        ],
        [
            'nama' => 'Meter Kubik',
            'description' => 'Satuan volume'
        ],
        [
            'nama' => 'Meter Persegi',
            'description' => 'Satuan luas'
        ],
        [
            'nama' => 'Kilogram',
            'description' => 'Satuan massa'
        ],
        [
            'nama' => 'Paket',
            'description' => 'Barang yang dikemas'
        ],
        [
            'nama' => 'Dokumen',
            'description' => 'Berkas dokumen'
        ],
        [
            'nama' => 'Buah',
            'description' => 'Barang satuan buah'
        ],
        [
            'nama' => 'Lembar',
            'description' => 'Lembar kertas'
        ],
        [
            'nama' => 'Pcs',
            'description' => 'Barang satuan'
        ],
        [
            'nama' => 'Pak',
            'description' => 'Satuan pak'
        ],
        [
            'nama' => 'Unit',
            'description' => 'Unit tunggal'
        ],
        [
            'nama' => 'Tahun',
            'description' => 'Satuan waktu satu tahun'
        ],
        [
            'nama' => 'Bulan',
            'description' => 'Satuan waktu satu bulan'
        ],
        [
            'nama' => 'Hari',
            'description' => 'Satuan waktu satu hari'
        ],
        [
            'nama' => 'Jam',
            'description' => 'Satuan waktu satu jam'
        ],
        [
            'nama' => 'Menit',
            'description' => 'Satuan waktu satu menit'
        ],
        [
            'nama' => 'Detik',
            'description' => 'Satuan waktu satu detik'
        ],
        [
            'nama' => 'Orang / Bulan',
            'description' => 'Pengukuran sumber daya manusia per bulan'
        ],
        [
            'nama' => 'Orang / Tahun',
            'description' => 'Pengukuran sumber daya manusia per tahun'
        ],
        [
            'nama' => 'Orang / Hari',
            'description' => 'Pengukuran sumber daya manusia per hari'
        ],
        [
            'nama' => 'Orang / Bulan',
            'description' => 'Pengukuran sumber daya manusia per bulan'
        ],
        [
            'nama' => 'Orang / Tahun',
            'description' => 'Pengukuran sumber daya manusia per tahun'
        ],
        [
            'nama' => 'Orang / Hari',
            'description' => 'Pengukuran sumber daya manusia per hari'
        ],
        [
            'nama' => 'Dus',
            'description' => 'Satuan kontainer'
        ],
        [
            'nama' => 'Kilometer',
            'description' => 'Satuan jarak'
        ],
        [
            'nama' => 'Tablet',
            'description' => 'Satuan obat'
        ],
        [
            'nama' => 'Ampul',
            'description' => 'Botol kecil yang disegel'
        ],
        [
            'nama' => 'Cubic Centimeter',
            'description' => 'Satuan volume'
        ],
        [
            'nama' => 'Kodi',
            'description' => 'Satuan kuantitas (20 item)'
        ],
        [
            'nama' => 'Kotak',
            'description' => 'Kotak kontainer'
        ],
        [
            'nama' => 'Liter',
            'description' => 'Satuan volume'
        ],
        [
            'nama' => 'Liter / Bulan',
            'description' => 'Volume per bulan'
        ],
        [
            'nama' => 'Liter / Tahun',
            'description' => 'Volume per tahun'
        ],
        [
            'nama' => 'Liter / Hari',
            'description' => 'Volume per hari'
        ],
        [
            'nama' => 'Kilogram',
            'description' => 'Satuan massa'
        ],
        [
            'nama' => 'Kilogram / Bulan',
            'description' => 'Massa per bulan'
        ],
        [
            'nama' => 'Kilogram / Tahun',
            'description' => 'Massa per tahun'
        ],
        [
            'nama' => 'Kilogram / Hari',
            'description' => 'Massa per hari'
        ],
        [
            'nama' => 'Pcs / Bulan',
            'description' => 'Kuantitas unit per bulan'
        ],
        [
            'nama' => 'Pcs / Tahun',
            'description' => 'Kuantitas unit per tahun'
        ],
        [
            'nama' => 'Pcs / Hari',
            'description' => 'Kuantitas unit per hari'
        ],
    ];
    

    public function definition(): array
    {
        // The definition method is left empty because we are not using it here
        return [];
    }

    public function predefinedData(): array
    {
        return static::$elements;
    }
}
