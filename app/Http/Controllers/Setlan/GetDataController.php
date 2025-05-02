<?php

namespace App\Http\Controllers\Setlan;

use App\Http\Controllers\Controller;
use App\Models\MKab;
use App\Models\MOpd;
use Cookie;
use Illuminate\Http\Request;

class GetDataController extends Controller
{
    public function getkabupaten(Request $request, $type = null , $id = null)
    {
        $keyword = $request->input('search');

        $kab = MKab::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('id_kab', '=', $keyword)
                    ->orWhere('nama_kab', 'LIKE', "%{$keyword}%");
            })
            ->paginate(10);
            $kab->getCollection()->transform(fn ($item) => [
                    'id'    => $item->id_kab,
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
            $id = Cookie::get('id_kab');
        }

        $opd = MOpd::query()
            ->when($keyword, function ($query) use ($keyword, $id) {
                $query->where('id_kab', '=', $id)
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
}
