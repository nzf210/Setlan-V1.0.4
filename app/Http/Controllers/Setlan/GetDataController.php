<?php

namespace App\Http\Controllers\Setlan;

use App\Http\Controllers\Controller;
use App\Models\KabupatenModel;
use App\Models\OpdModel;
use Cookie;
use Illuminate\Http\Request;

class GetDataController extends Controller
{
    public function getkabupaten(Request $request, $type = null , $id = null)
    {
        $keyword = $request->input('search');

        $kab = KabupatenModel::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('id_kabupaten', '=', $keyword)
                    ->orWhere('nama_kab', 'LIKE', "%{$keyword}%");
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
}
