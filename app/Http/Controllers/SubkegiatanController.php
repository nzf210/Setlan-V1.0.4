<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubKegiatanResource;
use App\Models\KegiatanModel;
use App\Models\SubKegiatanAktifModel;
use App\Models\SubKegiatanModel;
use App\Models\TahunModel;
use Cookie;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

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
        $kegiatanQuery = KegiatanModel::where('m_kegs.tahun', $tahun_aktif->keg)
            ->select(
                'm_kegs.id as id', // Add primary key for kegiatan
                'id_keg as kode',
                'nama',
                DB::raw("'keg' as type"),
                'm_kegs.tahun',
                'id_keg as parent_id'
            )
            ->when($search, function($query) use ($search) {
                $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('id_keg', 'like', "%{$search}%");
            });
        $kegiatan = KegiatanModel::where('m_kegs.tahun', $tahun_aktif->keg)
                    ->when($search, function($query) use ($search) {
                        $query->where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('id_keg', 'like', "%{$search}%");
                    });
        // Query untuk Sub Kegiatan
        $subkegiatanQuery = SubKegiatanModel::where('m_subkegs.tahun', $tahun_aktif->keg)
            ->with('keg')
            ->join('m_kegs', function($join) {
                $join->on('m_subkegs.id_keg', '=', 'm_kegs.id_keg')
                    ->on('m_subkegs.tahun', '=', 'm_kegs.tahun');
            })
            ->select(
                'm_subkegs.id as id', // Add primary key for subkegiatan
                'm_subkegs.id_subkeg as kode',
                'm_subkegs.nama',
                DB::raw("'sub' as type"),
                'm_subkegs.tahun',
                'm_kegs.id_keg as parent_id'
            )
            ->when($search, function($query) use ($search) {
                $query->where('m_subkegs.nama', 'LIKE', "%{$search}%")
                    ->orWhere('id_subkeg', 'like', "%{$search}%");
            });

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
