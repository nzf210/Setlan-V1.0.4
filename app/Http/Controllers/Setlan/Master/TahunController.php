<?php

namespace App\Http\Controllers\Setlan\Master;

use App\Http\Controllers\Controller;
use App\Models\TahunModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
class TahunController extends Controller
{

    public function index()
    {
        $idkab = Cookie::get('id_kabupaten');
        $tahun = TahunModel::where('id_kabupaten', $idkab)->get();
        return back()->with('value', $tahun);
    }
    public function view()
    {
        $idkab = Cookie::get('id_kabupaten');
        $tahun = TahunModel::where('id_kabupaten', $idkab)->get();
        return Inertia::render('Setlan/Tahun/Index',
        ['tahun' => $tahun,
                'can' => [
                            'create' => auth()->user()->can('create', TahunModel::class),
                            'edit' => auth()->user()->can('update', TahunModel::class),
                            'delete' => auth()->user()->can('delete', TahunModel::class)
                        ],
            ]);
    }

    public function store(Request $request, TahunModel $tahun)
    {
        Gate::authorize('create', $tahun);
        $idkab = Cookie::get('id_kabupaten');
        $request['id_kabupaten'] = $idkab;
        $validated = $request->validate([
            'tahun' => 'required|numeric',
            'id_kabupaten' => 'required|numeric'
        ]);

        TahunModel::create([
            'id_kabupaten' => $validated['id_kabupaten'],
            'tahun' => $validated['tahun'],
            'tahun_akun' => $validated['tahun'],
            'tahun_kegiatan' => $validated['tahun'],
            'tahun_sub_kegiatan' => $validated['tahun'],
            'tahun_kode_barang' => $validated['tahun'],
        ]);

        return redirect()->back();
    }

    public function update(Request $request, $id, TahunModel $tahun)
    {

        Gate::authorize('update', $tahun);
        $tahun = TahunModel::findOrFail($id);
        $validated = $request->validate([
            'tahun_akun' => TAHUN,
            'tahun_kegiatan' => TAHUN,
            'tahun_sub_kegiatan' => TAHUN,
            'tahun_kode_barang' => TAHUN
        ]);

        if ($request->has('tahun_kegiatan')) {
            $validated['tahun_sub_kegiatan'] = $validated['tahun_kegiatan'];
        }

        $tahun->update($validated);
        return redirect()->back();
    }

    public function destroy(TahunModel $tahun, $id)
    {
        Gate::authorize('delete', $tahun);
        $tahun->where('id_tahun', $id)->delete();
        return redirect()->back();
    }

}
