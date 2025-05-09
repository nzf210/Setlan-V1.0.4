<?php

namespace App\Http\Controllers\Setlan\Master;

use Cookie;
use App\Models\OpdModel;
use App\Models\TahunModel;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use App\Models\KabupatenModel;
use App\Http\Controllers\Controller;

class GetDataController extends Controller
{
    public function getkabupaten(Request $request, $type = null , $id = null)
    {
        $keyword = $request->input('search');

        $kab = KabupatenModel::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('id_kabupaten', '=', $keyword)
                    ->orWhere('nama_kabupaten', 'LIKE', "%{$keyword}%");
            })
            ->paginate(10);
            $kab->getCollection()->transform(fn ($item) => [
                    'id'    => $item->id_kabupaten,
                    'name' => $item->nama_kab
                ]
            );
        return back()->with([
            'value' => $kab,
            'search' => $keyword,
        ]);
    }
    public function getopd(Request $request, $type = null , $id= null)
    {
        $keyword = $request->input('search');

        if (!$id) {
            $id = Cookie::get('id_kabupaten');
        }

        $opd = OpdModel::query()
            ->when($keyword, function ($query) use ($keyword, $id) {
                $query->where('id_kabupaten', '=', $id)
                    ->orWhere('id_opd', 'LIKE', "%{$keyword}%")
                    ->orWhere('nama_opd', 'LIKE', "%{$keyword}%");
            })
            ->paginate(10);
            $opd->getCollection()->transform(fn ($item) => [
                    'id'    => $item->id_opd,
                    'name' => $item->nama_opd
                ]
            );
        return back()->with([
            'value' => $opd,
            'search' => $keyword,
        ]);
    }
    public function getkegiatan(Request $request,  $id= null)
    {
        $tahun = Cookie::get('tahun');

        $tahun_aktif = TahunModel::where(['tahun' => $tahun])->first();

        if (!$id && $tahun_aktif) {
            $id = Cookie::get('id_kabupaten');
        }
        $kegiatan = KegiatanModel::where(['id_kegiatan' => $id , 'tahun' => $tahun_aktif->tahun_kegiatan])->get();
        return back()->with([
            'value' => $kegiatan,
        ]);
    }
}
