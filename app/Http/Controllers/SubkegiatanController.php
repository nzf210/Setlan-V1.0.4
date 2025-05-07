<?php

namespace App\Http\Controllers;

use DB;
use Cookie;
use Inertia\Inertia;
use App\Models\TahunModel;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use Illuminate\Validation\Rule;
use App\Models\SubKegiatanModel;
use App\Models\SubKegiatanAktifModel;
use App\Http\Resources\SubKegiatanResource;

class SubkegiatanController extends Controller
{
    public function show(Request $request)
    {
        $mutasiController =  new MutasiController();
        $idKab = $mutasiController->getCookies()[0];
        $idOpd = $mutasiController->getCookies()[1];
        $idUnit = $mutasiController->getCookies()[2];
        $tahun = $mutasiController->getCookies()[3];
        $m_subkeg = collect();
        if($tahun && $idKab && $idOpd && $idUnit){
            $m_subkeg = SubKegiatanAktifModel::with(['subKegiatan'])
            ->where(['id_kabupaten' => $idKab,
                                'id_opd' => $idOpd,
                                'id_unit' => $idUnit,
                                'tahun' => $tahun])->paginate(10);
        }

        $m_subkegResource = SubKegiatanResource::collection($m_subkeg);
        return redirect()->back()->with(["value" =>  $m_subkegResource]);
    }

    public function index( Request $request )
    {
        $search = $request->input('search');
        $perPage = 10;
        $tahun = Cookie::get('tahun');

        // Validasi tahun
        $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
        if (!$tahun || !$tahun_aktif) {
            return back()->withErrors([
                'system' => SESI
            ]);
        }

        // Query untuk Kegiatan
        $kegiatanQuery = KegiatanModel::where('kegiatan.tahun', $tahun_aktif->tahun_kegiatan)
            ->select(
                'kegiatan.id_kegiatan as id_kegiatan', // Add primary key for kegiatan
                'kode_kegiatan',
                'nama_kegiatan',
                    DB::raw("'keg' as type"),
                'kegiatan.tahun',
                'id_kegiatan as parent_id'
            );
        $kegiatan = KegiatanModel::where('kegiatan.tahun', $tahun_aktif->tahun_kegiatan)
                    ->when($search, function($query) use ($search) {
                        $query->where('nama_kegiatan', 'LIKE', "%{$search}%")
                            ->orWhere('kode_kegiatan', 'like', "%{$search}%");
                    });
        // Query untuk Sub Kegiatan
        $subkegiatanQuery = SubKegiatanModel::where('sub_kegiatan.tahun', $tahun_aktif->tahun_sub_kegiatan)
            ->with('kegiatan')
            ->join('kegiatan', function($join) {
                $join->on('sub_kegiatan.id_kegiatan', '=', 'kegiatan.id_kegiatan')
                    ->on('sub_kegiatan.tahun', '=', 'kegiatan.tahun');
            })
            ->select(
                'sub_kegiatan.id_sub_kegiatan as id_sub_kegiatan', // Add primary key for subkegiatan
                'sub_kegiatan.kode_sub_kegiatan as kode_sub_kegiatan',
                'sub_kegiatan.nama_sub_kegiatan as nama_sub_kegiatan',
                DB::raw("'sub' as type"),
                'sub_kegiatan.tahun',
                'kegiatan.id_kegiatan as parent_id'
            )
            ->when($search, function($query) use ($search) {
                $query->where('sub_kegiatan.nama_sub_kegiatan', 'LIKE', "%{$search}%")
                    ->orWhere('kode_sub_kegiatan', 'like', "%{$search}%");
            });

        dd($subkegiatanQuery);
        // Gabungkan query
        $results = $kegiatanQuery->unionAll($subkegiatanQuery)
            ->orderBy('parent_id')
            ->orderBy('type')
            ->orderBy('kode')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Setlan/Kegiatan/SubKegiatanIndex', [
            'subkegiatans' => $results,
            'kegiatan' => $kegiatan->paginate($perPage)->withQueryString(),
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ]
        ]);

    }

    public function store(Request $request)
{
        $tahun = Cookie::get('tahun');

        // Validasi tahun
        $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
        if (!$tahun || !$tahun_aktif) {
            return back()->withErrors([
                'system' => SESI
            ]);
        }
        $request['tahun'] = $tahun_aktif->keg;

    if($request->namasub === 'nsub'){
        $request['id_subkeg'] = $request->kode;
    } else {
        $request['id_keg'] = $request->kode;
    }

    $request->validate([
        'id_subkeg' => [
            'required',
            'string',
            'max:50',
            Rule::unique('m_subkegs')->where(fn ($query) =>
                    $query->where('id_keg', $request->id_keg)
                    ->where('tahun', $tahun_aktif->keg)
            )
        ],
        'nama' => 'required|string|max:255',
        'id_keg' => 'required|exists:m_kegs,id_keg',
        'tahun' => 'required|exists:years,year'
    ]);

    try {
        DB::transaction(function () use ($request, $tahun_aktif) {
            SubKegiatanModel::create([
                'id_subkeg' => $request->id_subkeg,
                'nama' => $request->nama,
                'id_keg' => $request->id_keg,
                'tahun' => $tahun_aktif->keg
            ]);
        });

        return redirect()->back()->with('success', 'Sub Kegiatan berhasil dibuat');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal membuat sub kegiatan: ' . $e->getMessage());
    }
}

    public function update(Request $request, SubKegiatanModel $subkegiatan)
{

    $tahun = Cookie::get('tahun');
    $tahun_aktif = TahunModel::where('tahun', $tahun)->first();

    // Validasi tahun
    if (!$tahun_aktif) {
        return back()->withErrors(['system' => SESI]);
    }


    try {
        $validated = $request->validate([
            'id_subkeg' => [
                'required',
                'string',
                'max:50',
                Rule::unique('m_subkegs')->where(fn ($query) => $query
                    ->where('id_keg', $request->id_keg)
                    ->where('tahun', $tahun_aktif->year)
                )->ignore($request->id)
            ],
            'nama' => 'required|string',
            'id_keg' => 'required|exists:m_kegs,id_keg'
        ]);

        // Update data
        $subkegiatan->where('id', $request->id)->update([
            'id_subkeg' => $validated['id_subkeg'],
            'nama' => $validated['nama'],
            'id_keg' => $validated['id_keg'],
            'tahun' => $tahun_aktif->year
        ]);

        return redirect()->back()->with('success', 'Sub Kegiatan berhasil diperbarui');
    } catch (\Exception $e) {
        dd($e);
        return redirect()->back()->with('error', 'Gagal memperbarui sub kegiatan: ' . $e->getMessage());
    }
}

    public function destroy(SubKegiatanModel $subkegiatan, $id)
    {

        try {
            DB::transaction(function () use ($subkegiatan, $id) {
                $subkegiatan->where('id', $id)->delete();
            });

            return redirect()->back()->with('success', 'Sub Kegiatan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus sub kegiatan: ' . $e->getMessage());
        }
    }
}
