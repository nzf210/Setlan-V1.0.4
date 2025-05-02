<?php

namespace App\Http\Controllers\Setlan;

use App\Http\Controllers\Controller;
use App\Http\Resources\MSubKegResource;
use App\Models\MKab;
use App\Models\MOpd;
use App\Models\MSubkeg;
use App\Models\MUnit;
use App\Models\UnitSubKeg;
use App\Models\UserKabupaten;
use App\Models\UserOpd;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UnitSubKegController extends Controller
{

    public function index()
{
    $user = auth()->user();
    $role = $user->roles->first()?->name ?? 'guest';
    $idKabUser = UserKabupaten::where('id_user', $user->id)->value('id_kab');
    $idOpd = UserOpd::where('id_user', $user->id)->value('id_opd');

    $initialData = [
        'kabupatens' => $role === 'super_admin' ? MKab::all() : [],
        'opds' => match ($role) {
            'admin_kab' => MOpd::where('id_kab', $idKabUser)->get(),
            'admin_opd', 'operator' => MOpd::where('id_opd', $idOpd)->get(),
            default => [],
        },
        'units' => in_array($role, ['admin_opd', 'operator'])
            ? MUnit::where('id_opd', $idOpd)->get()
            : [],
    ];

    $unitSubKeg = $this->getUnitSubKeg();
    $unitRes = MSubKegResource::collection($unitSubKeg);
    return Inertia::render('Setlan/UnitSubKeg/Index', [
        'initialData' => $initialData,
        'unitSubKegs' => $unitRes,
    ]);
}

    public function getOpds($id_kab) {
        $data = MOpd::where('id_kab', operator: $id_kab)->get();
        return back()->with('value', $data);
    }

    public function getUnits(Request $request, $id_opd) {
        $data = MUnit::where('id_opd', $id_opd)->get();
        return back()->with('value', $data);
    }

    public function getSubKegiatans(Request $request) {

        $query = MSubkeg::query();
        $query->with(['kegs'])->when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%"))
        ->orWhere('id_subkeg', 'like', "%{$request->search}%");
        $data = $query->limit(10)->get();
        return back()->with('value', $data);
    }

    public function saveData(Request $request) {
        $tahun = Cookie::get('year');
        $user = auth()->user();

        $idKabUser = UserKabupaten::where('id_user', $user->id)->pluck('id_kab')->first();
        $idOpd = UserOpd::where('id_user', $user->id)->pluck('id_opd')->first();
        $role = $user->roles->pluck('name')[0];

        if ($role === 'super_admin') {
            $request['kabupaten_id'] = $request->input('kabupaten_id');
        } elseif ($role === 'admin_kab') {
            $request['kabupaten_id'] = $idKabUser;
        } elseif ($role === 'admin_opd') {
            $request['kabupaten_id'] = $idKabUser;
            $request['opd_id'] = $idOpd;
        }

        try {

        $request['tahun'] = $tahun;
        $validated = $request->validate([
            'kabupaten_id' => 'required',
            'opd_id' => 'required',
            'unit_id' => 'required',
            'id_subkeg' => 'required',
            'tahun' => [
                'required',
                'exists:years,year',
            ],
        ]);
        UnitSubKeg::create([
            'id_kab' => $validated['kabupaten_id'],
            'id_opd' => $validated['opd_id'],
            'id_unit' => $validated['unit_id'],
            'id_subkeg' => $validated['id_subkeg'],
            'tahun' => $validated['tahun'],
        ]);
        // Simpan data ke database atau lakukan operasi lain
        return back()
            ->with('success', 'Data berhasil disimpan!')
            ->with('unitSubKegs', $this->getUnitSubKeg());
    } catch (\Exception $e) {
        return back()->with('error', 'Data gagal disimpan!.');
    }
    }

    public function destroy(string $id)
    {
        try {
            $unitSubKeg = UnitSubKeg::findOrFail($id);
            $unitSubKeg->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function getUnitSubKeg(string $idOrName = null)
    {
        $user = auth()->user();
        $role = $user->roles->first()?->name ?? 'guest';
        $idKabUser = UserKabupaten::where('id_user', $user->id)->value('id_kab');
        $idOpd = UserOpd::where('id_user', $user->id)->value('id_opd');

        $query = UnitSubKeg::with([
            'kabupaten',
            'opd',
            'unit',
            'subKegiatan.kegs'
        ])->where('tahun', Cookie::get('year'));

        $query->when($idOrName, function ($q) use ($idOrName) {
            $q->where('id_subkeg', 'like', "%{$idOrName}%")
                ->orWhereHas('subKegiatan', function ($q) use ($idOrName) {
                    $q->where('nama', 'like', "%{$idOrName}%");
                });
        });

        switch ($role) {
            case 'admin_kab':
                $query->where('id_kab', $idKabUser);
                break;

            case 'admin_opd':
            case 'operator':
                $query->where('id_opd', $idOpd);
                break;

            case 'super_admin':
                break;

            default:
                return [];
        }

        return $query->paginate(10);
    }

    public function getUnitSubkegiatan(string $idOrName = null ){
        $unitSubKeg = $this->getUnitSubKeg($idOrName);
        $unitRes = MSubKegResource::collection($unitSubKeg);
        return back()->with('value', $unitRes);
    }
}
