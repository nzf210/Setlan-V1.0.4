<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\TahunModel;
use Illuminate\Http\Request;
use App\Models\KodeBarangModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\QueryException;

class KodeBarangController extends Controller
{
    public function get(Request $request){
        $tahun = Cookie::get('tahun');
        $idKabupaten = Cookie::get('id_kabupaten');
        $tahun_aktif = TahunModel::where(['tahun' => $tahun, 'id_kabupaten' => $idKabupaten])->first();
        $query = KodeBarangModel::whereRaw('LENGTH(id_kd_barang) = 20')->where(['id_kabupaten' => $idKabupaten, 'tahun' => $tahun_aktif->tahun_kode_barang]);
        $search = $request->has('nama_kode_barang');
        if ($search && $request->nama != '') {
            if (preg_match('/[0-9\.]/', $request->nama)) {
                $query->where('kode_barang', 'like', "%{$request->nama_kode_barang}%");
            } else {
                $query->where('nama_kode_barang', 'like', "%{$request->nama_kode_barang}%");
            }
        }
        $kdBarang = $query->paginate(10)->withQueryString();
        return back()->with('value', $kdBarang);
    }

    public function index(Request $request)
    {
        $idKab = Cookie::get('id_kabupaten');
        $tahun = Cookie::get('tahun');
        $tahun_aktif = TahunModel::where(['tahun' => $tahun, 'id_kabupaten' => $idKab])->first();
        if (!$idKab || !$tahun || !$tahun_aktif) {
            return back()->withErrors([
                'system' => SESI
            ]);
        }
        $query = KodeBarangModel::query()
            ->where(    ['id_kabupaten' => $idKab, 'tahun' => $tahun_aktif->tahun_kode_barang])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_kode_barang', 'like', "%{$search}%");
                });
            })
            ->orderBy('tahun', 'desc')
            ->orderBy('kode_barang');

        $kodeBarang = $query->with('kabupaten')->paginate(10)->withQueryString();
        $tahun = TahunModel::where(['tahun' => $tahun, 'id_kabupaten' => $idKab])->pluck('tahun')->sortDesc()->values();

        return Inertia::render('Setlan/Barang/KodeBarang/Index', [
            'kodeBarang' => $kodeBarang,
            'tahun' => $tahun,
            'filters' => $request->only(['search']),
            'can' => [
                'create' => auth()->user()->can('create', KodeBarangModel::class),
                'edit' => auth()->user()->can('update', KodeBarangModel::class),
                'delete' => auth()->user()->can('delete', KodeBarangModel::class)
            ],
        ]);
    }

    public function store(Request $request)
{
    $idKab = Cookie::get('id_kabupaten');
    $tahun = Cookie::get('tahun');
    $tahun_aktif = TahunModel::where(['tahun' => $tahun , 'id_kabupaten' => $idKab])->first();
    if (!$idKab || !$tahun || !$tahun_aktif) {
        return back()->withErrors([
            'system' => 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.'
        ]);
    }

    $request['id_kabupaten'] = $idKab;
    $request['tahun'] = $tahun_aktif->tahun_kode_barang;

    $validated = $request->validate([
        'kode_barang' => [
            'required',
            'string',
            'max:255',
            Rule::unique('kode_barang')->where(fn ($query) => $query
                ->where('id_kabupaten', $request->id_kabupaten)
                ->where('tahun', $tahun_aktif->tahun)
            )
        ],
        'nama_kode_barang' => LONG_STR,
        'id_kabupaten' => REQ_NUM,
        'tahun' => REQ_NUM,
    ]);
    try {
        // Create with combined data
        KodeBarangModel::create([
            ...$validated,
            'id_kabupaten' => $validated['id_kabupaten'],
            'tahun' => $validated['tahun'],
        ]);

    } catch (QueryException $e) {
        // Handle duplicate entry despite validation
        if ($e->errorInfo[1] === 1062) {
            return back()->withErrors([
                'id_kd_barang' => 'Kombinasi ini sudah ada untuk kabupaten dan tahun aktif Anda'
            ])->withInput();
        }

        // Re-throw other database exceptions
        throw $e;
    }

    return redirect()->route('setlan.pengaturan.kodebarang')
                    ->with('success', 'Kode barang berhasil ditambahkan.');
}

    public function update(Request $request, KodeBarangModel $kodeBarang)
{
    // Authorization check
    Gate::authorize('update', $kodeBarang);
    $idKab = Cookie::get('id_kabupaten');
    $tahun = Cookie::get('tahun');
    $tahun_aktif = TahunModel::where(['tahun' => $tahun , 'id_kabupaten' => $idKab])->first();
    if (!$idKab || !$tahun || !$tahun_aktif) {
        return back()->withErrors([
            'system' => 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.'
        ]);
    }

    $request['id_kabupaten'] = $idKab;
    $request['tahun'] = $tahun_aktif->tahun_kode_barang;
    try {
        $validated = $request->validate([
            'id_kode_barang' => REQ_NUM,
            'kode_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kode_barang', 'kode_barang')
                    ->where('id_kabupaten', $request->id_kabupaten)
                    ->where('tahun', $request->tahun)
                    ->ignore($request->id_kode_barang,'id_kode_barang')
            ],
            'nama_kode_barang' => 'required|string|max:255',
        ]);

            $kodeBarang->where('id_kode_barang', $validated['id_kode_barang'])->update([
                'kode_barang' => $validated['kode_barang'],
                'nama_kode_barang' => $validated['nama_kode_barang'],
            ]);

            return redirect()->route('setlan.pengaturan.kodebarang')
                            ->with('success', 'Kode barang berhasil diperbarui.');
    } catch (\Throwable $th) {
        return redirect()->route('setlan.pengaturan.kodebarang')
                            ->with('error', $th->getMessage());
    }

}

    public function destroy(KodeBarangModel $kdBarang, $id)
    {
        Gate::authorize('delete', $kdBarang);
        try {
            $kdBarang->where('id_kode_barang', $id)->delete();
            return redirect()->route('setlan.pengaturan.kodebarang')->with('success', 'Kode barang berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('setlan.pengaturan.kodebarang')->with('error', $th->getMessage());
        }
    }
}
