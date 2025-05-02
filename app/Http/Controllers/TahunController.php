<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;
use Inertia\Inertia;
define('TAHUN', 'sometimes|integer|exists:years,year');
class TahunController extends Controller
{

    public function index()
    {
        $tahun = Year::all();
        return back()->with('value', $tahun);
    }
    public function view()
    {
        $tahun = Year::all();
        return Inertia::render('Setlan/Tahun/Index', ['tahun' => $tahun]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|unique:years,year',
        ]);

        Year::create([
            'year' => $validated['year'],
            'akun' => $validated['year'],
            'keg' => $validated['year'],
            'sub_keg' => $validated['year'],
        ]);

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $tahun = Year::findOrFail($id);

        $validated = $request->validate([
            'akun' => TAHUN,
            'keg' => TAHUN,
            'sub_keg' => TAHUN,
            'kd_id_barang' => 'sometimes|integer'
        ]);

        if ($request->has('keg')) {
            $validated['sub_keg'] = $validated['keg'];
        }
        $tahun->update($validated);

        return redirect()->back();
    }

    public function destroy(Year $year, $id)
    {
        $year->where('id', $id)->delete();
        return redirect()->back();
    }

}
