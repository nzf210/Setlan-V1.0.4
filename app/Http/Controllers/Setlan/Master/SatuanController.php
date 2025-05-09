<?php

namespace App\Http\Controllers\Setlan\Master;
use App\Models\SatuanModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SatuanController extends Controller
{
    public function index(Request $request){
        $search = $request->has('nama');
        $satuan = SatuanModel::filtered()->paginate(10)->withQueryString();
        if ($search && $request->nama != '') {
                $satuan->where('nama', 'like', "%{$request->nama}%");
        }
        return back()->with(['value' => $satuan]);
    }
}
