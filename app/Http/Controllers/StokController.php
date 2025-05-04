<?php

namespace App\Http\Controllers;

use App\Http\Resources\MutasiResource;
use App\Models\Mutasi;
use App\Models\MutasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

define("ST", "Stock");
class StokController extends Controller
{
    public function index()
    {
        try {

            $mutasiController =  new MutasiController();
            $idKab = $mutasiController->getCookies()[0];
            $idOpd = $mutasiController->getCookies()[1];
            $idUnit = $mutasiController->getCookies()[2];

            $filterCondition = 'greater'; // or 'less_or_equal' for jumlah <= 0
            $brgMasuk = MutasiModel::with(['barang.category', 'barang.akun', 'barang.satuan', 'subkeg', 'kabupaten', 'opd', 'unit'])
                ->select(
                    'mutasi.id_barang',
                    'mutasi.id_kab',
                    'mutasi.id_opd',
                    'mutasi.id_unit',
                    'barangs.harga',
                    'mutasi.id_subkeg',
                    'categories.name as category_name',
                    'akunaktif.r6 as r6',
                    'akunaktif.nama as akun_name',
                    'satuans.nama as satuan_name',
                    'm_subkegs.nama as subkeg_name',
                    'm_kabs.nama_kab as nama_kab',
                    'm_opds.nama_opd as nama_opd',
                    'm_units.nama_unit as nama_unit',
                    DB::raw('SUM(mutasi.jumlah) as jumlah'),
                    DB::raw('SUM(mutasi.jumlah) * barangs.harga as total_price')
                )
                ->join('barangs', 'mutasi.id_barang', '=', 'barangs.id_barang')
                ->join('categories', 'barangs.category_id', '=', 'categories.id')
                ->join('akunaktif', 'barangs.r6', '=', 'akunaktif.r6')
                ->join('satuans', 'barangs.satuan_id', '=', 'satuans.id')
                ->join('m_subkegs', 'mutasi.id_subkeg', '=', 'm_subkegs.id_subkeg')
                ->join('m_kabs', 'mutasi.id_kab', '=', 'm_kabs.id_kab')
                ->join('m_opds', 'mutasi.id_opd', '=', 'm_opds.id_opd')
                ->join('m_units', 'mutasi.id_unit', '=', 'm_units.id_unit')
                ->where([
                    'mutasi.id_kab' => $idKab,
                    'mutasi.id_unit' => $idUnit,
                    'mutasi.id_opd' => $idOpd
                ])
                ->whereIn('mutasi.type', ['masuk', 'sawal', 'keluar']);

            // Apply the filter condition based on the variable
            if ($filterCondition == 'greater') {
                $brgMasuk->havingRaw('SUM(mutasi.jumlah) > 0');
            } else {
                $brgMasuk->havingRaw('SUM(mutasi.jumlah) <= 0');
            }

            $brgmasuk = $brgMasuk->groupBy(
                'mutasi.id_barang',
                'mutasi.id_kab',
                'mutasi.id_opd',
                'mutasi.id_unit',
                'barangs.harga',
                'mutasi.id_subkeg',
                'categories.name',
                'akunaktif.r6',
                'akunaktif.nama',
                'satuans.nama',
                'm_subkegs.nama',
                'm_kabs.nama_kab',
                'm_opds.nama_opd',
                'm_units.nama_unit'
            );

            $groupedData = [];

            $wp = $brgmasuk->filtered()->paginate(25)->withQueryString();
            $wpt = $brgmasuk->filtered()->paginate(-1)->withQueryString();
            foreach ($wpt as $item) {
                $barangKey = $item['id_barang'];
                if (!isset($groupedData[$barangKey])) {
                    $groupedData[$barangKey] = [
                        'barang' => $item['barang'],
                        'total_jumlah' => 0,
                        'total_price' => 0
                    ];
                }

                $jumlah = (int) $item['jumlah'];
                $harga = (float) $item['harga'];
                $totharga = $jumlah * $harga;
                $groupedData[$barangKey]['total_price'] += $totharga;
                $groupedData[$barangKey]['akun_name'] = $item['akun_name'];
            }

            $groupeddata = [];
            foreach ($groupedData as $group) {
                $akunkey = $group['barang']['akun']['r6'];
                if (!isset($groupeddata[$akunkey])) {
                    $groupeddata[$akunkey] = [
                        'total_jumlah' => 0
                    ];
                }
                $harga = (float) $group['total_price'];
                $groupeddata[$akunkey]['total_jumlah'] += $harga;
                $groupeddata[$akunkey]['akun_name'] = $group['akun_name'];
            }

            return Inertia::render('Setlan/Barang/Stock/StockBarang',
            [   'data' => MutasiResource::collection($wp),
                'calc' => $groupeddata,
                'breadcrumb' => [
                                ['name' => BR, 'url' => '#'],
                                ['name' => MB, 'url' => route('setlan.barang.master')],
                                ['name' => ST, 'url' => '#'],
                                ['name' => 'Stock Barang', 'url' => route('barang.stock')],
                            ]
                ])->with('success', ST);
        } catch (\Throwable $th) {
            //// throw $th;
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }

    public function expiredrusak()
    {
        try {
            $mutasiController =  new MutasiController();
            $idKab = $mutasiController->getCookies()[0];
            $idOpd = $mutasiController->getCookies()[1];
            $idUnit = $mutasiController->getCookies()[2];

            $filterCondition = 'greater'; // or 'less_or_equal' for jumlah <= 0
            $brgMasuk = MutasiModel::with(['barang.category', 'barang.akun', 'barang.satuan', 'subkeg', 'kabupaten', 'opd', 'unit'])
                ->select(
                    'mutasi.id_barang',
                    'mutasi.id_kab',
                    'mutasi.id_opd',
                    'mutasi.id_unit',
                    'barangs.harga',
                    'mutasi.id_subkeg',
                    'categories.name as category_name',
                    'akunaktif.r6 as r6',
                    'akunaktif.nama as akun_name',
                    'satuans.nama as satuan_name',
                    'm_subkegs.nama as subkeg_name',
                    'm_kabs.nama_kab as nama_kab',
                    'm_opds.nama_opd as nama_opd',
                    'm_units.nama_unit as nama_unit',
                    DB::raw('SUM(mutasi.jumlah) as jumlah'),
                    DB::raw('SUM(mutasi.jumlah) * barangs.harga as total_price')
                )
                ->join('barangs', 'mutasi.id_barang', '=', 'barangs.id_barang')
                ->join('categories', 'barangs.category_id', '=', 'categories.id')
                ->join('akunaktif', 'barangs.r6', '=', 'akunaktif.r6')
                ->join('satuans', 'barangs.satuan_id', '=', 'satuans.id')
                ->join('m_subkegs', 'mutasi.id_subkeg', '=', 'm_subkegs.id_subkeg')
                ->join('m_kabs', 'mutasi.id_kab', '=', 'm_kabs.id_kab')
                ->join('m_opds', 'mutasi.id_opd', '=', 'm_opds.id_opd')
                ->join('m_units', 'mutasi.id_unit', '=', 'm_units.id_unit')
                ->where([
                    'mutasi.id_kab' => $idKab,
                    'mutasi.id_unit' => $idUnit,
                    'mutasi.id_opd' => $idOpd
                ])
                ->whereIn('mutasi.type', ['masuk', 'sawal', 'keluar']);

            // Apply the filter condition based on the variable
            if ($filterCondition == 'greater') {
                $brgMasuk->havingRaw('SUM(mutasi.jumlah) > 0');
            } else {
                $brgMasuk->havingRaw('SUM(mutasi.jumlah) <= 0');
            }

            $brgmasuk = $brgMasuk->groupBy(
                'mutasi.id_barang',
                'mutasi.id_kab',
                'mutasi.id_opd',
                'mutasi.id_unit',
                'barangs.harga',
                'mutasi.id_subkeg',
                'categories.name',
                'akunaktif.r6',
                'akunaktif.nama',
                'satuans.nama',
                'm_subkegs.nama',
                'm_kabs.nama_kab',
                'm_opds.nama_opd',
                'm_units.nama_unit'
            );

            $groupedData = [];

            $wp = $brgmasuk->filtered()->paginate(25)->withQueryString();
            $wpt = $brgmasuk->filtered()->paginate(-1)->withQueryString();
            foreach ($wpt as $item) {
                $barangKey = $item['id_barang'];
                if (!isset($groupedData[$barangKey])) {
                    $groupedData[$barangKey] = [
                        'barang' => $item['barang'],
                        'total_jumlah' => 0,
                        'total_price' => 0
                    ];
                }

                $jumlah = (int) $item['jumlah'];
                $harga = (float) $item['harga'];
                $totharga = $jumlah * $harga;
                $groupedData[$barangKey]['total_price'] += $totharga;
                $groupedData[$barangKey]['akun_name'] = $item['akun_name'];
            }

            $groupeddata = [];
            foreach ($groupedData as $group) {
                $akunkey = $group['barang']['akun']['r6'];
                if (!isset($groupeddata[$akunkey])) {
                    $groupeddata[$akunkey] = [
                        'total_jumlah' => 0
                    ];
                }
                $harga = (float) $group['total_price'];
                $groupeddata[$akunkey]['total_jumlah'] += $harga;
                $groupeddata[$akunkey]['akun_name'] = $group['akun_name'];
            }
            return Inertia::render('Setlan/Barang/Stock/ExpiredRusak',
            [   'data' => MutasiResource::collection($wp),
                'calc' => $mutasiController->show(['sawal','masuk','keluar']),
                'breadcrumb' => [
                                ['name' => BR, 'url' => '#'],
                                ['name' => MB, 'url' => route('setlan.barang.master')],
                                ['name' => ST, 'url' => '#'],
                                ['name' => 'Stock Barang', 'url' => route('barang.expired')],
                            ]
                ])->with('success', ST);
        } catch (\Throwable $th) {
           // //throw $th;

        dd('oks', $th);
        }
    }
}
