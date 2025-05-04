<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Setlan\SubKegiatanAktifController;
use App\Http\Controllers\Setlan\UnitSubKegController;
use App\Http\Resources\MutasiDraftMasukResource;
use App\Http\Resources\MutasiResource;
use App\Http\Resources\SubKegiatanResource;
use App\Models\KabupatenModel;
use App\Models\MutasiModel;
use App\Models\OpdModel;
use App\Models\UnitModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

define("BRG_MSK", "Berhasil Menambah barang masuk.");
define("MB", "Master Barang");
define("MM", "Mutasi Masuk");
define("BR", "Barang");
class MutasiController extends Controller
{
    public function index()
    {
        $mkab =  KabupatenModel::get()->sortBy(['id_kabupaten']);
        $mopd =  OpdModel::get()->sortBy(['id_opd']);
        $munit =  UnitModel::get()->sortBy(['id_unit']);
        return redirect()->back()->with(['mkab' => $mkab, 'mopd' => $mopd, 'munit' => $munit]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $idKab = $this->getCookies()[0];
        $idOpd = $this->getCookies()[1];
        $idUnit = $this->getCookies()[2];
        $tahun = $this->getCookies()[3];
        $request->validate(['id_barang' => 'required']);

        /** Cek apakah sudah ada di draft atau belum */
        $barangMutasi = MutasiModel::where(['type' => 'draft_masuk','id_barang' => $request->id_barang,
                                        'id_kabupaten' => $idKab,'id_opd' => $idOpd,'id_unit' => $idUnit, 'tahun' => $tahun])->first();
        if ($barangMutasi) {
            return redirect()->back()->with('info', 'Barang sudah di tambahkan ke list draft.');
            } else {
                $mutasi = new MutasiModel();
                $mutasi->id_barang = $request->id_barang;
                $mutasi->id_unit = $idUnit;
                $mutasi->id_opd = $idOpd;
                $mutasi->id_kab = $idKab;
                $mutasi->tahun = $tahun;
                $mutasi->tgl_beli = date('Y-m-d');
                $mutasi->created_by = Auth::user()->id;
                $mutasi->updated_by = Auth::user()->id;
                $mutasi->save();
                return redirect()->back()->with('success', 'Berhasil Menambah ke draft barang masuk.');
            }
    }

    public function mutasiMasukDraft(Request $request){
        try {
                $idKab = $this->getCookies()[0];
                $idOpd = $this->getCookies()[1];
                $idUnit = $this->getCookies()[2];
                $tahun = $this->getCookies()[3];
                $search = $request->input('search');
                $search_sub = $request->input('search_sub');

                $query = MutasiModel::where(["type" => "draft_masuk","id_kab" => $idKab, "id_opd" => $idOpd, "id_unit" => $idUnit , "tahun" => $tahun])
                            ->with(['unit','opd','kabupaten','barang.category','barang.akun','barang.satuan','subkeg.subKegiatan']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('id_barang', 'like', "%{$search}%")
                            ->orWhereHas('barang', function ($bq) use ($search) {
                                $bq->where('nama_barang', 'like', "%{$search}%");
                            })
                            ->orWhereHas('subkeg.subKegiatan', function ($skq) use ($search) {
                                $skq->where('nama', 'like', "%{$search}%");
                            });
                    });
                }

                $dataMutasi = $query->paginate(10);
                $dataMutasiResource = MutasiDraftMasukResource::collection($dataMutasi);
                $datasub = (new SubKegiatanAktifController())->getUnitSubKeg($search_sub);
                $subKegiatanResource = SubKegiatanResource::collection($datasub);
                            return Inertia::render('Setlan/Barang/Masuk/Draft',
                            [    'data' =>  $dataMutasiResource,
                                        'calc' => $this->show(['draft_masuk']),
                                        'subkeg' => $subKegiatanResource,
                                        'breadcrumb' => [
                                                            ['name' => BR, 'url' => '#'],
                                                            ['name' => MB, 'url' => route('setlan.barang.master')],
                                                            ['name' => MM, 'url' => '#'],
                                                            ['name' => 'Draft', 'url' => route('setlan.barang.masukdraft')],
                                                        ]
                                ])->with('success', 'Berhasil Menambah ke draft barang masuk.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function mutasiMasukDraftDate(Request $request, $id,$id_barang){
        try {
            $idKab = $this->getCookies()[0];
            $idOpd = $this->getCookies()[1];
            $idUnit = $this->getCookies()[2];
            $tahun = $this->getCookies()[3];
            $draft = MutasiModel::where(['id'=> $id, 'id_barang' => $id_barang, 'id_kabupaten' => $idKab,
            'id_opd' => $idOpd, 'id_unit' => $idUnit, 'type' => 'draft_masuk', 'tahun' => $tahun])
            ->firstOrFail();
            $draft->tgl_beli = $request->tgl_beli;
            $draft->updated_by = Auth::user()->id;
            $draft->save();
    } catch (ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    }
    }
    public function mutasiMasukDraftPajak(Request $request, $id,$id_barang){
        try {
            $idKab = $this->getCookies()[0];
            $idOpd = $this->getCookies()[1];
            $idUnit = $this->getCookies()[2];
            $tahun = $this->getCookies()[3];
            $draft = MutasiModel::where
                ([
                    'id'=> $id, 'id_barang' => $id_barang, 'id_kabupaten' => $idKab,
                    'id_opd' => $idOpd, 'id_unit' => $idUnit, 'type' => 'draft_masuk',
                    'tahun' => $tahun
                ])
            ->firstOrFail();
            $draft->pajak = $request->pajak;
            $draft->updated_by = Auth::user()->id;
            $draft->save();
    } catch (ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    }
}

    public function mutasiMasukDraftDelete(Request $request, $id,$id_barang){
        try {
                if ($id || $id_barang ) {
                    $idKab = $this->getCookies()[0];
                    $idOpd = $this->getCookies()[1];
                    $idUnit = $this->getCookies()[2];
                    $tahun = $this->getCookies()[3];
                } else {
                    return back()->withErrors('Pilih unit terlebih dahulu.')->withInput();
                }
                MutasiModel::where(['id'=> $id, 'id_barang' => $id_barang, 'id_kabupaten' => $idKab,
                'id_opd' => $idOpd, 'id_unit' => $idUnit, 'type' => 'draft_masuk' , 'tahun' => $tahun])
                ->firstOrFail()->delete();
                return back()->with('success', 'Berhasil Menghapus Draft.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
    public function mutasiMasukDraftUpdate(Request $request, $id, $id_barang){
            try {
                    $idKab = $this->getCookies()[0];
                    $idOpd = $this->getCookies()[1];
                    $idUnit = $this->getCookies()[2];
                    $tahun = $this->getCookies()[3];
                    if($request->type === 'jumlah'){
                        $draft = MutasiModel::where(['id'=> $id, 'id_barang' => $id_barang, 'id_kabupaten' => $idKab,
                            'id_opd' => $idOpd, 'id_unit' => $idUnit, 'type' => 'draft_masuk', 'tahun' => $tahun])
                            ->firstOrFail();
                            $draft->jumlah = $request->jumlah;
                            $draft->updated_by = Auth::user()->id;
                            $draft->save();
                    }
                    if($request->type === 'pajak'){
                        $draft = MutasiModel::where(['id'=> $id, 'id_barang' => $id_barang, 'id_kabupaten' => $idKab,
                            'id_opd' => $idOpd, 'id_unit' => $idUnit, 'type' => 'draft_masuk' , 'tahun' => $tahun])
                            ->firstOrFail();
                            $draft->pajak = $request->pajak < 0 ? 0 : $request->pajak ;
                            $draft->updated_by = Auth::user()->id;
                            $draft->save();
                    }
                    if($request->type === 'penyesuaian'){
                        $draft = MutasiModel::where(['id'=> $id, 'id_barang' => $id_barang, 'id_kabupaten' => $idKab,
                            'id_opd' => $idOpd, 'id_unit' => $idUnit, 'type' => 'draft_masuk' , 'tahun' => $tahun])
                            ->firstOrFail();
                            $draft->penyesuaian = $request->penyesuaian < 0 ? 0 : $request->penyesuaian;
                            $draft->updated_by = Auth::user()->id;
                            $draft->save();
                    }
                    if($request->type === 'id_subkeg'){
                        if($request->subkeg != '0'){
                            $draft = MutasiModel::where(['id'=> $id])
                            ->firstOrFail();
                            $draft->id_subkeg = $request->subkeg;
                            $draft->updated_by = Auth::user()->id;
                            $draft->save();
                        } else {
                            return back()->withErrors('Pilih sub kegiatan terlebih dahulu.')->withInput();
                        }
                    }
            } catch (ValidationException $e) {
                return back()->withErrors($e->errors())->withInput();
            }
    }

    public function getCookies(){
        if ( request()->hasCookie('id_kabupaten') || request()->hasCookie('id_unit') || request()->hasCookie('id_opd') || request()->hasCookie('tahun')) {
            $idKab = request()->cookie('id_kabupaten');
            $idUnit = request()->cookie('id_unit');
            $idOpd = request()->cookie('id_opd');
            $tahun = request()->cookie('tahun');
            return [$idKab, $idOpd,$idUnit, $tahun];
        } else {
            return back()->withErrors('Pilih unit terlebih dahulu.')->withInput();
        }
    }

    public function mutasiBarangMasuk(){
        try {
            $idKab = $this->getCookies()[0];
            $idOpd = $this->getCookies()[1];
            $idUnit = $this->getCookies()[2];
            $tahun = $this->getCookies()[3];
            $brgMasuk = MutasiModel::where(
                [   'id_unit'=>$idUnit,
                    'id_opd'=>$idOpd,
                    'type'=>'masuk',
                    'id_kabupaten'=>$idKab,
                    'tahun'=>$tahun
                ])
            ->with(['unit','opd','kabupaten','barang.category','barang.akun','barang.satuan','subkeg']);
            $wp = $brgMasuk->filtered()->paginate(25)->withQueryString();
            return Inertia::render('Setlan/Barang/Masuk/BarangMasuk',

            [   'data' => MutasiResource::collection($wp),
                'calc' => $this->show(['masuk']),
                'breadcrumb' => [
                                ['name' => BR, 'url' => '#'],
                                ['name' => MB, 'url' => route('setlan.barang.master')],
                                ['name' => MM, 'url' => '#'],
                                ['name' => 'Barang Masuk', 'url' => route('setlan.barang.masukbarang')],
                            ]
                ])->with('success', BRG_MSK);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
    public function mutasiSaldoAwal(){
        try {
            $idKab = $this->getCookies()[0];
            $idOpd = $this->getCookies()[1];
            $idUnit = $this->getCookies()[2];
            $sAwal = MutasiModel::where(['id_unit'=>$idUnit, 'id_opd'=>$idOpd, 'type'=>'sawal', 'id_kabupaten'=>$idKab])
            ->with(['unit','opd','kabupaten','barang.category','barang.akun','barang.satuan','subkeg']);
            $wp = $sAwal->filtered()->paginate(25)->withQueryString();
            return Inertia::render('Setlan/Barang/Masuk/Sawal',
            [   'data' => $wp,
                'calc' => $this->show(['sawal']),
                'breadcrumb' => [
                                ['name' => BR, 'url' => '#'],
                                ['name' => MB, 'url' => route('setlan.barang.master')],
                                ['name' => MM, 'url' => '#'],
                                ['name' => 'Saldo Awal', 'url' => route('setlan.barang.saldoAwal')],
                            ]
                ])->with('success', 'Berhasil Menambah Saldo Awal.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function mutasiMasukDraftStore(Request $request)
    {
        try {
            $idKab = $this->getCookies()[0];
            $idOpd = $this->getCookies()[1];
            $idUnit = $this->getCookies()[2];

            $request->validate([
                'data' => 'required|array',
                'type' => 'required|string',
            ]);

            $redirectData = [
                'route' => route('setlan.barang.masukbarang'),
                'message' => BRG_MSK
            ];

            // Handle saldo awal
            if ($request->type === 'sawal') {
                MutasiModel::where($this->getCommonConditions($idKab, $idOpd, $idUnit))->delete();
                $redirectData['route'] = route('setlan.barang.saldoAwal');
            }

            // Validasi data
            throw_if(empty($request->data), \Exception::class, 'Tidak ada data yang diproses');

            // Update data
            MutasiModel::whereIn('id', $request->data)->update([
                'type' => $request->type,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect($redirectData['route'])->with('success', $redirectData['message']);

        } catch (\Throwable $e) {
            return $e instanceof ValidationException
                ? back()->withErrors($e->errors())->withInput()
                : back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // Helper function untuk kondisi query
    private function getCommonConditions($idKab, $idOpd, $idUnit): array
    {
        return [
            'type' => 'sawal',
            'id_kabupaten' => $idKab,
            'id_opd' => $idOpd,
            'id_unit' => $idUnit
        ];
    }
    /**
     * Display the specified resource.
     */
    public function show($type)
    {
        $idKab = $this->getCookies()[0];
        $idOpd = $this->getCookies()[1];
        $idUnit = $this->getCookies()[2];
        $tahun = $this->getCookies()[3];
        $mutasiR6 = MutasiModel::with(['barang', 'barang.akun'])->where(['id_kabupaten' => $idKab, 'id_opd' => $idOpd, 'id_unit' => $idUnit , 'tahun' => $tahun])
        ->whereIn('type', $type)
        ->get();

        $groupedR6 = $mutasiR6->groupBy(fn ($mutasi) => $mutasi->barang->akun->id);
        $granTotalR6 = 0;
        foreach ($groupedR6 as &$msR6) {
            foreach ($msR6 as $ms) {
                $jumlah = $ms['jumlah'];
                $penyesuaian = $ms['penyesuaian'];
                $harga = $ms['barang']['harga'];
                $pajak = $ms['pajak'];
                $subTot = ($harga + $penyesuaian) * $jumlah;
                $total = $pajak === 0 ? $subTot : $subTot * $pajak / 100;
                $ms['total'] = $total ;
                $granTotalR6 += $total;
                $ms['nama'] = $ms['barang']['akun']['nama'];
            }
            $msR6['rek'] = $msR6[0]['barang']['akun']['id'];
        }

        return [
            'akun' => $groupedR6,
            'akun_s6' => ['akun_s6' => $mutasiR6, 'total' => $granTotalR6],
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MutasiModel $mutasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MutasiModel $mutasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MutasiModel $mutasi)
    {
        //
    }
}
