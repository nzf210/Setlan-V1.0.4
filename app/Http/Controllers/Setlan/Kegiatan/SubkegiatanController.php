<?php

namespace App\Http\Controllers\Setlan\Kegiatan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Setlan\Persediaan\MutasiController;
use App\Http\Resources\SubKegiatanAktifResource;
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
            $m_subkeg = SubKegiatanAktifModel::with(['sub_kegiatan'])
            ->where(['id_kabupaten' => $idKab,
                                'id_opd' => $idOpd,
                                'id_unit' => $idUnit,
                                'tahun' => $tahun])->paginate(10);
        }

        $m_subkegResource = SubKegiatanAktifResource::collection($m_subkeg);
        return redirect()->back()->with(["value" =>  $m_subkegResource]);
    }

    public function index( Request $request )
    {
        $search = $request->input('search');
        $perPage = 10;
        $tahun = Cookie::get('tahun');
        $idKabupaten = Cookie::get('id_kabupaten');

        // Validasi tahun
        $tahun_aktif = TahunModel::where(['tahun' => $tahun, 'id_kabupaten' => $idKabupaten])->first();
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
                'kegiatan.id_kegiatan as parent_id',
            )
            ->when($search, function($query) use ($search) {
                $query->where('sub_kegiatan.nama_sub_kegiatan', 'LIKE', "%{$search}%")
                    ->orWhere('kode_sub_kegiatan', 'like', "%{$search}%");
            });

        // dd($subkegiatanQuery);
        // Gabungkan query
        $results = $kegiatanQuery->unionAll($subkegiatanQuery)
            ->orderBy('parent_id')
            ->orderBy('type')
            ->orderBy('kode_kegiatan')
            ->orderBy('nama_kegiatan')
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
        $idKabupaten = Cookie::get('id_kabupaten');

        // Validasi tahun
        if (!$tahun || !$idKabupaten) {
            return back()->withErrors([
                'system' => SESI
            ]);
        }
        $tahun_aktif = TahunModel::where(['tahun' => $tahun, 'id_kabupaten' => $idKabupaten])->first();
        $request['tahun'] = $tahun_aktif->tahun_sub_kegiatan;

        $validated = $request->validate([
                    'kode_sub_kegiatan' => [
                                    'required',
                                    'string',
                                    'regex:/^[0-9.]+$/',
                                        Rule::unique('sub_kegiatan', 'kode_sub_kegiatan')
                                        ->where(fn($query) => $query->where(['tahun' => $tahun_aktif->tahun_sub_kegiatan , 'id_kegiatan' => $request->id_kegiatan]))],
                    'nama_sub_kegiatan' => 'required|string|max:255',
                    'id_kegiatan' => 'required|exists:kegiatan,id_kegiatan',
                    'tahun' => TAHUN
    ]);

    try {
        DB::transaction(function () use ($validated) {
            SubKegiatanModel::create([
                'kode_sub_kegiatan' => $validated['kode_sub_kegiatan'],
                'nama_sub_kegiatan' => $validated['nama_sub_kegiatan'],
                'id_kegiatan' => $validated['id_kegiatan'],
                'tahun' => $validated['tahun']
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
    $idKabupaten = Cookie::get('id_kabupaten');
    $tahun_aktif = TahunModel::where(['tahun' => $tahun , 'id_kabupaten' => $idKabupaten])->first();

    if (!$tahun_aktif || !$idKabupaten) {
        return back()->withErrors(['system' => SESI]);
    }
    $request['tahun'] = $tahun_aktif->tahun_sub_kegiatan;
    try {
        $validated = $request->validate([
                'kode_sub_kegiatan' => [
                            'required',
                            'string',
                            'regex:/^[0-9.]+$/',
                                Rule::unique('sub_kegiatan', 'kode_sub_kegiatan')
                                ->where(fn($query) => $query->where(['tahun' => $tahun_aktif->tahun_sub_kegiatan , 'id_kegiatan' => $request->id_kegiatan]))
                                ->ignore($request->id_sub_kegiatan, 'id_sub_kegiatan')
                        ],
                        'nama_sub_kegiatan' => 'required|string|max:255',
                        'id_kegiatan' => 'required|exists:kegiatan,id_kegiatan',
                        'tahun' => TAHUN
            ]);

        // Update data
        $subkegiatan->where(['id_sub_kegiatan' => $request->id_sub_kegiatan , 'id_kegiatan' => $request->id_kegiatan])->update([
            'kode_sub_kegiatan' => $validated['kode_sub_kegiatan'],
            'nama_sub_kegiatan' => $validated['nama_sub_kegiatan']
        ]);

        return redirect()->back()->with('success', 'Sub Kegiatan berhasil diperbarui');
    } catch (\Exception $e) {

        return redirect()->back()->with('error', 'Gagal memperbarui sub kegiatan: ' . $e->getMessage());
    }
}

    public function destroy(SubKegiatanModel $subkegiatan, $id)
    {

        try {
            DB::transaction(function () use ($subkegiatan, $id) {
                $subkegiatan->where('id_sub_kegiatan', $id)->delete();
            });

            return redirect()->back()->with('success', 'Sub Kegiatan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus sub kegiatan: ' . $e->getMessage());
        }
    }
}
