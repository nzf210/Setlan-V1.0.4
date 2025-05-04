<?php

namespace Database\Factories;

use App\Models\OpdModel;
use App\Models\UnitModel;
use Illuminate\Database\Eloquent\Factories\Factory;


class UnitModelFactory extends Factory
{
    protected static $index = 0;
    protected static $unit = [
        [
            "id_opd" =>  "1.01.0.00.0.00.01.0000",
            "id_unit" => "1.01.0.00.0.00.01.0000",
            "nama_unit" => "DINAS PENDIDIKAN"
        ],
        [
            "id_opd" => "1.02.0.00.0.00.01.0000",
            "id_unit" => "1.02.0.00.0.00.01.0000",
            "nama_unit" => "DINAS KESEHATAN"
        ],
        [
            "id_opd" => "1.02.0.00.0.00.02.0000",
            "id_unit" => "1.02.0.00.0.00.02.0000",
            "nama_unit" => "RUMAH SAKIT UMUM DAERAH"
        ],
        [
            "id_opd" => "1.03.0.00.0.00.01.0000",
            "id_unit" => "1.03.0.00.0.00.01.0000",
            "nama_unit" => "DINAS PEKERJAAN UMUM DAN PENATAAN RUANG"
        ],
        [
            "id_opd" => "1.04.0.00.0.00.01.0000",
            "id_unit" => "1.04.0.00.0.00.01.0000",
            "nama_unit" => "DINAS PERUMAHAN RAKYAT DAN KAWASAN PERMUKIMAN"
        ],
        [
            "id_opd" => "1.05.0.00.0.00.01.0000",
            "id_unit" => "1.05.0.00.0.00.01.0000",
            "nama_unit" => "SATUAN POLISI PAMONG PRAJA"
        ],
        [
            "id_opd" => "1.05.0.00.0.00.04.0000",
            "id_unit" => "1.05.0.00.0.00.04.0000",
            "nama_unit" => "BADAN PENANGGULANGAN BENCANA DAERAH"
        ],
        [
            "id_opd" => "1.06.0.00.0.00.01.0000",
            "id_unit" => "1.06.0.00.0.00.01.0000",
            "nama_unit" => "DINAS SOSIAL"
        ],
        [
            "id_opd" => "2.07.2.17.0.00.06.0000",
            "id_unit" => "2.07.2.17.0.00.06.0000",
            "nama_unit" => "DINAS TENAGA KERJA, KOPERASI, USAHA KECIL DAN MENENGAH"
        ],
        [
            "id_opd" => "2.08.2.14.0.00.01.0000",
            "id_unit" => "2.08.2.14.0.00.01.0000",
            "nama_unit" => "DINAS PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK DAN KELUARGA BERENCANA"
        ],
        [
            "id_opd" => "2.11.0.00.0.00.01.0000",
            "id_unit" => "2.11.0.00.0.00.01.0000",
            "nama_unit" => "DINAS LINGKUNGAN HIDUP"
        ],
        [
            "id_opd" => "2.12.0.00.0.00.01.0000",
            "id_unit" => "2.12.0.00.0.00.01.0000",
            "nama_unit" => "DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL"
        ],
        [
            "id_opd" => "2.13.0.00.0.00.01.0000",
            "id_unit" => "2.13.0.00.0.00.01.0000",
            "nama_unit" => "DINAS PEMBERDAYAAN MASYARAKAT DAN KAMPUNG"
        ],
        [
            "id_opd" => "2.15.0.00.0.00.01.0000",
            "id_unit" => "2.15.0.00.0.00.01.0000",
            "nama_unit" => "DINAS PERHUBUNGAN"
        ],
        [
            "id_opd" => "2.16.2.20.2.21.01.0000",
            "id_unit" => "2.16.2.20.2.21.01.0000",
            "nama_unit" => "DINAS KOMUNIKASI DAN INFORMATIKA"
        ],
        [
            "id_opd" => "2.18.0.00.0.00.01.0000",
            "id_unit" => "2.18.0.00.0.00.01.0000",
            "nama_unit" => "DINAS PENANAMAN MODAL DAN PERIJINAN TERPADU SATU PINTU"
        ],
        [
            "id_opd" => "2.19.1.01.0.00.01.0000",
            "id_unit" => "2.19.1.01.0.00.01.0000",
            "nama_unit" => "DINAS PEMUDA DAN OLAHRAGA"
        ],
        [
            "id_opd" => "2.22.3.26.0.00.03.0000",
            "id_unit" => "2.22.3.26.0.00.03.0000",
            "nama_unit" => "DINAS KEBUDAYAAN DAN PARIWISATA"
        ],
        [
            "id_opd" => "2.23.2.24.0.00.02.0000",
            "id_unit" => "2.23.2.24.0.00.02.0000",
            "nama_unit" => "DINAS PERPUSTAKAAN DAN ARSIP DAERAH"
        ],
        [
            "id_opd" => "3.25.0.00.0.00.01.0000",
            "id_unit" => "3.25.0.00.0.00.01.0000",
            "nama_unit" => "DINAS PERIKANAN"
        ],
        [
            "id_opd" => "3.27.2.09.0.00.02.0000",
            "id_unit" => "3.27.2.09.0.00.02.0000",
            "nama_unit" => "DINAS PERTANIAN DAN KETAHANAN PANGAN"
        ],
        [
            "id_opd" => "3.31.3.30.1.04.02.0000",
            "id_unit" => "3.31.3.30.1.04.02.0000",
            "nama_unit" => "DINAS PERINDUSTRIAN DAN PERDAGANGAN"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0000",
            "nama_unit" => "SEKRETARIAT DAERAH"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0001",
            "nama_unit" => "BAGIAN HUKUM"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0002",
            "nama_unit" => "BAGIAN PEMERINTAHAN"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0003",
            "nama_unit" => "BAGIAN PEREKONOMIAN"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0004",
            "nama_unit" => "BAGIAN KESEJAHTERAAN RAKYAT"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0005",
            "nama_unit" => "BAGIAN HUBUNGAN MASYARAKAT"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0006",
            "nama_unit" => "BAGIAN UMUM"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0007",
            "nama_unit" => "BAGIAN PENGADAAN BARANG / JASA"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0008",
            "nama_unit" => "BAGIAN ORGANISASI DAN TATA LAKSANA"
        ],
        [
            "id_opd" => "4.01.1.06.8.01.01.0000",
            "id_unit" => "4.01.1.06.8.01.01.0009",
            "nama_unit" => "BAGIAN PERBATASAN"
        ],
        [
            "id_opd" => "4.02.0.00.0.00.01.0000",
            "id_unit" => "4.02.0.00.0.00.01.0000",
            "nama_unit" => "SEKRETARIAT DPRD"
        ],
        [
            "id_opd" => "5.01.5.02.5.05.01.0000",
            "id_unit" => "5.01.5.02.5.05.01.0000",
            "nama_unit" => "BADAN PERENCANAAN PEMBANGUNAN DAERAH"
        ],
        [
            "id_opd" => "5.02.0.00.0.00.03.0000",
            "id_unit" => "5.02.0.00.0.00.03.0000",
            "nama_unit" => "BADAN PENDAPATAN DAERAH"
        ],
        [
            "id_opd" => "5.02.5.05.0.00.02.0000",
            "id_unit" => "5.02.5.05.0.00.02.0000",
            "nama_unit" => "BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH"
        ],
        [
            "id_opd" => "5.03.5.04.5.05.01.0000",
            "id_unit" => "5.03.5.04.5.05.01.0000",
            "nama_unit" => "BADAN KEPEGAWAIAN DAN SUMBER DAYA APARATUR"
        ],
        [
            "id_opd" => "6.01.0.00.0.00.01.0000",
            "id_unit" => "6.01.0.00.0.00.01.0000",
            "nama_unit" => "INSPEKTORAT"
        ],
        [
            "id_opd" => "7.01.0.00.0.00.07.0000",
            "id_unit" => "7.01.0.00.0.00.07.0000",
            "nama_unit" => "DISTRIK SUPIORI TIMUR"
        ],
        [
            "id_opd" => "7.01.0.00.0.00.09.0000",
            "id_unit" => "7.01.0.00.0.00.09.0000",
            "nama_unit" => "DISTRIK SUPIORI SELATAN"
        ],
        [
            "id_opd" => "7.01.0.00.0.00.10.0000",
            "id_unit" => "7.01.0.00.0.00.10.0000",
            "nama_unit" => "DISTRIK KEPULAUAN ARURI"
        ],
        [
            "id_opd" => "7.01.0.00.0.00.11.0000",
            "id_unit" => "7.01.0.00.0.00.11.0000",
            "nama_unit" => "DISTRIK SUPIORI BARAT"
        ],
        [
            "id_opd" => "7.01.2.13.0.00.08.0000",
            "id_unit" => "7.01.2.13.0.00.08.0000",
            "nama_unit" => "DISTRIK SUPIORI UTARA"
        ],
        [
            "id_opd" => "1.00.0.00.0.00.00.0001",
            "id_unit" => "1.00.0.00.0.00.00.0001",
            "nama_unit" => "DISTRIK UNIT UJI COBA"
        ],
        [
            "id_opd" => "1.11.3.33.0.00.00.0001",
            "id_unit" => "1.11.3.33.0.00.00.0001",
            "nama_unit" => "UNIT UJI COBA I"
        ],
        [
            "id_opd" => "1.11.3.33.0.00.00.0003",
            "id_unit" => "1.11.3.33.0.00.00.0013",
            "nama_unit" => "UNIT UJI COBA IIIa"
        ],
        [
            "id_opd" => "1.11.3.33.0.00.00.0003",
            "id_unit" => "1.11.3.33.0.00.00.0003",
            "nama_unit" => "UNIT UJI COBA III"
        ]
    ];
    protected $model = UnitModel::class;
    public function definition(): array
    {
        $unit = self::$unit[self::$index];
        self::$index = (self::$index + 1) % count(self::$unit);
        $opd = OpdModel::inRandomOrder()->first();

        return [
            'id_opd' => $opd->id_opd,
            'kode_unit' => $unit['id_unit'],
            'nama_unit' => $unit['nama_unit'],
        ];
    }
}
