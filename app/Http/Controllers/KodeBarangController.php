<?php

namespace App\Http\Controllers;

use App\Models\KodeBarangModel;
use App\Models\TahunModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class KodeBarangController extends Controller
{
    public function get(Request $request){
        $idKab = Cookie::get('id_kabupaten');
        $tahun = Cookie::get('tahun');
        $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
        $query = KodeBarangModel::whereRaw('LENGTH(id_kd_barang) = 20')->where(['id_kabupaten' => $idKab, 'tahun' => $tahun_aktif->kd_id_barang]);
        $search = $request->has('nama');
        if ($search && $request->nama != '') {
            if (preg_match('/[0-9\.]/', $request->nama)) {
                $query->where('id_kd_barang', 'like', "%{$request->nama}%");
            } else {
                $query->where('nama', 'like', "%{$request->nama}%");
            }
        }
        $kdBarang = $query->paginate(10)->withQueryString();
        return back()->with('value', $kdBarang);
    }

    public function index(Request $request)
    {
        $idKab = Cookie::get('id_kabupaten');
        $tahun = Cookie::get('tahun');
        $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
        $query = KodeBarangModel::query()
            ->where(    ['id_kabupaten' => $idKab, 'tahun' => $tahun_aktif->kd_id_barang])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id_kd_barang', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
            })
            ->orderBy('tahun', 'desc')
            ->orderBy('id_kd_barang');
        return Inertia::render('Setlan/Barang/KodeBarang/Index', [
            'kodeBarang' => $query->with('kabupaten')->paginate(10)->withQueryString(),
            'years' => TahunModel::pluck('tahun')->sortDesc()->values(),
            'filters' => $request->only(['search']),
            'can' => [
                'create' => auth()->user()->can('create', KodeBarangModel::class),
                'edit' => auth()->user()->can('update', KodeBarangModel::class),
                'delete' => auth()->user()->can('delete', KodeBarangModel::class),
            ],
        ]);
    }

    public function store(Request $request)
{
    // Get required values from cookies
    $idKab = Cookie::get('id_kabupaten');
    $tahun = Cookie::get('tahun');
    $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
    // Validate required cookie values
    if (!$idKab || !$tahun || !$tahun_aktif) {
        return back()->withErrors([
            'system' => 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.'
        ]);
    }

    // Validate request with unique rule
    $validated = $request->validate([
        'id_kd_barang' => [
            'required',
            'string',
            'max:255',
            Rule::unique('kode_barang')->where(function ($query) use ($idKab, $tahun_aktif) {
                return $query->where('id_kabupaten', $idKab)
                            ->where('tahun', $tahun_aktif->kd_id_barang);
            })
        ],
        'nama' => 'required|string|max:255',
    ]);

    try {
        // Create with combined data
        KodeBarangModel::create([
            ...$validated,
            'id_kabupaten' => $idKab,
            'tahun' => $tahun_aktif->kd_id_barang,
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
    $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
    // Validate required cookie values
    if (!$idKab || !$tahun || !$tahun_aktif) {
        return back()->withErrors([
            'system' => 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.'
        ]);
    }
    // Validate request data
    try {
        $validated = $request->validate([
            'id_kd_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kode_barang', 'id_kd_barang')
                    ->where('id_kabupaten', $idKab)
                    ->where('tahun', $tahun_aktif->kd_id_barang)
                    ->ignore($request->id)
            ],
            'nama' => 'required|string|max:255',
            'id' => 'required|numeric',
        ]);

            $kodeBarang->where('id', $validated['id'])->update([
                'id_kd_barang' => $validated['id_kd_barang'],
                'nama' => $validated['nama'],
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
            $kdBarang->where('id', $id)->delete();
            return redirect()->route('setlan.pengaturan.kodebarang')->with('success', 'Kode barang berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('setlan.pengaturan.kodebarang')->with('error', $th->getMessage());
        }
    }
}
