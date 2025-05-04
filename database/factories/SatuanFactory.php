<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SatuanFactory extends Factory
{
    protected static $elements = [
        [
            'nama_satuan' => 'Meter',
            'description' => 'Satuan panjang'
        ],
        [
            'nama_satuan' => 'Meter Kubik',
            'description' => 'Satuan volume'
        ],
        [
            'nama_satuan' => 'Meter Persegi',
            'description' => 'Satuan luas'
        ],
        [
            'nama_satuan' => 'Kilogram',
            'description' => 'Satuan massa'
        ],
        [
            'nama_satuan' => 'Paket',
            'description' => 'Barang yang dikemas'
        ],
        [
            'nama_satuan' => 'Dokumen',
            'description' => 'Berkas dokumen'
        ],
        [
            'nama_satuan' => 'Buah',
            'description' => 'Barang satuan buah'
        ],
        [
            'nama_satuan' => 'Lembar',
            'description' => 'Lembar kertas'
        ],
        [
            'nama_satuan' => 'Pcs',
            'description' => 'Barang satuan'
        ],
        [
            'nama_satuan' => 'Pak',
            'description' => 'Satuan pak'
        ],
        [
            'nama_satuan' => 'Unit',
            'description' => 'Unit tunggal'
        ],
        [
            'nama_satuan' => 'Tahun',
            'description' => 'Satuan waktu satu tahun'
        ],
        [
            'nama_satuan' => 'Bulan',
            'description' => 'Satuan waktu satu bulan'
        ],
        [
            'nama_satuan' => 'Hari',
            'description' => 'Satuan waktu satu hari'
        ],
        [
            'nama_satuan' => 'Jam',
            'description' => 'Satuan waktu satu jam'
        ],
        [
            'nama_satuan' => 'Menit',
            'description' => 'Satuan waktu satu menit'
        ],
        [
            'nama_satuan' => 'Detik',
            'description' => 'Satuan waktu satu detik'
        ],
        [
            'nama_satuan' => 'Orang / Bulan',
            'description' => 'Pengukuran sumber daya manusia per bulan'
        ],
        [
            'nama_satuan' => 'Orang / Tahun',
            'description' => 'Pengukuran sumber daya manusia per tahun'
        ],
        [
            'nama_satuan' => 'Orang / Hari',
            'description' => 'Pengukuran sumber daya manusia per hari'
        ],
        [
            'nama_satuan' => 'Orang / Bulan',
            'description' => 'Pengukuran sumber daya manusia per bulan'
        ],
        [
            'nama_satuan' => 'Orang / Tahun',
            'description' => 'Pengukuran sumber daya manusia per tahun'
        ],
        [
            'nama_satuan' => 'Orang / Hari',
            'description' => 'Pengukuran sumber daya manusia per hari'
        ],
        [
            'nama_satuan' => 'Dus',
            'description' => 'Satuan kontainer'
        ],
        [
            'nama_satuan' => 'Kilometer',
            'description' => 'Satuan jarak'
        ],
        [
            'nama_satuan' => 'Tablet',
            'description' => 'Satuan obat'
        ],
        [
            'nama_satuan' => 'Ampul',
            'description' => 'Botol kecil yang disegel'
        ],
        [
            'nama_satuan' => 'Cubic Centimeter',
            'description' => 'Satuan volume'
        ],
        [
            'nama_satuan' => 'Kodi',
            'description' => 'Satuan kuantitas (20 item)'
        ],
        [
            'nama_satuan' => 'Kotak',
            'description' => 'Kotak kontainer'
        ],
        [
            'nama_satuan' => 'Liter',
            'description' => 'Satuan volume'
        ],
        [
            'nama_satuan' => 'Liter / Bulan',
            'description' => 'Volume per bulan'
        ],
        [
            'nama_satuan' => 'Liter / Tahun',
            'description' => 'Volume per tahun'
        ],
        [
            'nama_satuan' => 'Liter / Hari',
            'description' => 'Volume per hari'
        ],
        [
            'nama_satuan' => 'Kilogram',
            'description' => 'Satuan massa'
        ],
        [
            'nama_satuan' => 'Kilogram / Bulan',
            'description' => 'Massa per bulan'
        ],
        [
            'nama_satuan' => 'Kilogram / Tahun',
            'description' => 'Massa per tahun'
        ],
        [
            'nama_satuan' => 'Kilogram / Hari',
            'description' => 'Massa per hari'
        ],
        [
            'nama_satuan' => 'Pcs / Bulan',
            'description' => 'Kuantitas unit per bulan'
        ],
        [
            'nama_satuan' => 'Pcs / Tahun',
            'description' => 'Kuantitas unit per tahun'
        ],
        [
            'nama_satuan' => 'Pcs / Hari',
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
