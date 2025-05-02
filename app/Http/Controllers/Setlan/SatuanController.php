<?php

namespace App\Http\Controllers\Setlan;
use App\Http\Controllers\Controller;
use App\Models\SatuanModel;
use Illuminate\Http\Request;

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
