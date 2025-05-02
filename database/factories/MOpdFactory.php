<?php

namespace Database\Factories;

use App\Models\MKab;
use App\Models\MOpd;
use Illuminate\Database\Eloquent\Factories\Factory;

class MOpdFactory extends Factory
{
    protected $model = MOpd::class;
    protected static $index = 0;
    protected static $opd = [
        ['id_opd' => '1.01.0.00.0.00.01.0000', 'nama_opd' => 'DINAS PENDIDIKAN', 'id_kab' => '91.19'],
        ['id_opd' => '1.02.0.00.0.00.01.0000', 'nama_opd' => 'DINAS KESEHATAN', 'id_kab' => '91.19'],
        ['id_opd' => '1.02.0.00.0.00.02.0000', 'nama_opd' => 'RUMAH SAKIT UMUM DAERAH', 'id_kab' => '91.19'],
        ['id_opd' => '1.03.0.00.0.00.01.0000', 'nama_opd' => 'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG', 'id_kab' => '91.19'],
        ['id_opd' => '1.04.0.00.0.00.01.0000', 'nama_opd' => 'DINAS PERUMAHAN RAKYAT DAN KAWASAN PERMUKIMAN', 'id_kab' => '91.19'],
        ['id_opd' => '1.05.0.00.0.00.01.0000', 'nama_opd' => 'SATUAN POLISI PAMONG PRAJA', 'id_kab' => '91.19'],
        ['id_opd' => '1.05.0.00.0.00.04.0000', 'nama_opd' => 'BADAN PENANGGULANGAN BENCANA DAERAH', 'id_kab' => '91.19'],
        ['id_opd' => '1.06.0.00.0.00.01.0000', 'nama_opd' => 'DINAS SOSIAL', 'id_kab' => '91.19'],
        ['id_opd' => '2.07.2.17.0.00.06.0000', 'nama_opd' => 'DINAS TENAGA KERJA, KOPERASI, USAHA KECIL DAN MENENGAH', 'id_kab' => '91.19'],
        ['id_opd' => '2.08.2.14.0.00.01.0000', 'nama_opd' => 'DINAS PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK DAN KELUARGA BERENCANA', 'id_kab' => '91.19'],
        ['id_opd' => '2.11.0.00.0.00.01.0000', 'nama_opd' => 'DINAS LINGKUNGAN HIDUP', 'id_kab' => '91.19'],
        ['id_opd' => '2.12.0.00.0.00.01.0000', 'nama_opd' => 'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL', 'id_kab' => '91.19'],
        ['id_opd' => '2.13.0.00.0.00.01.0000', 'nama_opd' => 'DINAS PEMBERDAYAAN MASYARAKAT DAN KAMPUNG', 'id_kab' => '91.19'],
        ['id_opd' => '2.15.0.00.0.00.01.0000', 'nama_opd' => 'DINAS PERHUBUNGAN', 'id_kab' => '91.19'],
        ['id_opd' => '2.16.2.20.2.21.01.0000', 'nama_opd' => 'DINAS KOMUNIKASI DAN INFORMATIKA', 'id_kab' => '91.19'],
        ['id_opd' => '2.18.0.00.0.00.01.0000', 'nama_opd' => 'DINAS PENANAMAN MODAL DAN PERIJINAN TERPADU SATU PINTU', 'id_kab' => '91.19'],
        ['id_opd' => '2.19.1.01.0.00.01.0000', 'nama_opd' => 'DINAS PEMUDA DAN OLAHRAGA', 'id_kab' => '91.19'],
        ['id_opd' => '2.22.3.26.0.00.03.0000', 'nama_opd' => 'DINAS KEBUDAYAAN DAN PARIWISATA', 'id_kab' => '91.19'],
        ['id_opd' => '2.23.2.24.0.00.02.0000', 'nama_opd' => 'DINAS PERPUSTAKAAN DAN ARSIP DAERAH', 'id_kab' => '91.19'],
        ['id_opd' => '3.25.0.00.0.00.01.0000', 'nama_opd' => 'DINAS PERIKANAN', 'id_kab' => '91.19'],
        ['id_opd' => '3.27.2.09.0.00.02.0000', 'nama_opd' => 'DINAS PERTANIAN DAN KETAHANAN PANGAN', 'id_kab' => '91.19'],
        ['id_opd' => '3.31.3.30.1.04.02.0000', 'nama_opd' => 'DINAS PERINDUSTRIAN DAN PERDAGANGAN', 'id_kab' => '91.19'],
        ['id_opd' => '4.01.1.06.8.01.01.0000', 'nama_opd' => 'SEKRETARIAT DAERAH', 'id_kab' => '91.19'],
        ['id_opd' => '4.02.0.00.0.00.01.0000', 'nama_opd' => 'SEKRETARIAT DPRD', 'id_kab' => '91.19'],
        ['id_opd' => '5.01.5.02.5.05.01.0000', 'nama_opd' => 'BADAN PERENCANAAN PEMBANGUNAN DAERAH', 'id_kab' => '91.19'],
        ['id_opd' => '5.02.0.00.0.00.03.0000', 'nama_opd' => 'BADAN PENDAPATAN DAERAH', 'id_kab' => '91.19'],
        ['id_opd' => '5.02.5.05.0.00.02.0000', 'nama_opd' => 'BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH', 'id_kab' => '91.19'],
        ['id_opd' => '5.03.5.04.5.05.01.0000', 'nama_opd' => 'BADAN KEPEGAWAIAN DAN SUMBER DAYA APARATUR', 'id_kab' => '91.19'],
        ['id_opd' => '6.01.0.00.0.00.01.0000', 'nama_opd' => 'INSPEKTORAT', 'id_kab' => '91.19'],
        ['id_opd' => '7.01.0.00.0.00.07.0000', 'nama_opd' => 'DISTRIK SUPIORI TIMUR', 'id_kab' => '91.19'],
        ['id_opd' => '7.01.0.00.0.00.09.0000', 'nama_opd' => 'DISTRIK SUPIORI SELATAN', 'id_kab' => '91.19'],
        ['id_opd' => '7.01.0.00.0.00.10.0000', 'nama_opd' => 'DISTRIK KEPULAUAN ARURI', 'id_kab' => '91.19'],
        ['id_opd' => '7.01.0.00.0.00.11.0000', 'nama_opd' => 'DISTRIK SUPIORI BARAT', 'id_kab' => '91.19'],
        ['id_opd' => '7.01.2.13.0.00.08.0000', 'nama_opd' => 'DISTRIK SUPIORI UTARA', 'id_kab' => '91.19'],
        ['id_opd' => '1.00.0.00.0.00.00.0001', 'nama_opd' => 'UJI COBA', 'id_kab' => '01.01'],
        ['id_opd' => '1.11.3.33.0.00.00.0001', 'nama_opd' => 'UJI COBA I', 'id_kab' => '01.01'],
        ['id_opd' => '1.11.3.33.0.00.00.0002', 'nama_opd' => 'UJI COBA II', 'id_kab' => '01.01'],
        ['id_opd' => '1.11.3.33.0.00.00.0003', 'nama_opd' => 'UJI COBA III', 'id_kab' => '01.01'],
    ];

    public function definition(): array
    {

        $opd = self::$opd[self::$index];
        self::$index = (self::$index + 1) % count(self::$opd);
        return [
            'id_kab' => $opd['id_kab'],
            'id_opd' => $opd['id_opd'],
            'nama_opd' => $opd['nama_opd'],
        ];
    }
}
