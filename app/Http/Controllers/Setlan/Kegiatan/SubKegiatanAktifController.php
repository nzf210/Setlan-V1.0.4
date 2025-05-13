<?php

namespace App\Http\Controllers\Setlan\Kegiatan;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubKegiatanAktifResource;
use App\Models\KabupatenModel;
use App\Models\OpdModel;
use App\Models\SubKegiatanAktifModel;
use App\Models\SubKegiatanModel;
use App\Models\UnitModel;
use App\Models\UserKabupatenModel;
use App\Models\UserOpdModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SubKegiatanAktifController extends Controller
{

    public function index()
{
    $user = auth()->user();
    $role = $user->roles->first()?->name ?? 'guest';
    $idKabUser = UserKabupatenModel::where('id_user', $user->id)->value('id_kabupaten');
    $idOpd = UserOpdModel::where('id_user', $user->id)->value('id_opd');

    $initialData = [
        'kabupatens' => $role === 'super_admin' ? KabupatenModel::all() : [],
        'opds' => match ($role) {
            'admin_kab' => OpdModel::where('id_kabupaten', $idKabUser)->get(),
            'admin_opd', 'operator' => OpdModel::where('id_opd', $idOpd)->get(),
            default => [],
        },
        'units' => in_array($role, ['admin_opd', 'operator'])
            ? UnitModel::where('id_opd', $idOpd)->get()
            : [],
    ];

    $unitSubKeg = $this->getUnitSubKeg();
    $unitRes = SubKegiatanAktifResource::collection($unitSubKeg);
    return Inertia::render('Setlan/UnitSubKeg/Index', [
        'initialData' => $initialData,
        'unitSubKegs' => $unitRes,
    ]);
}

    public function getOpds($id_kabuten) {
        $data = OpdModel::where('id_kabupaten', operator: $id_kabuten)->get();
        return back()->with('value', $data);
    }

    public function getUnits(Request $request, $id_opd) {
        $data = UnitModel::where('id_opd', $id_opd)->get();
        return back()->with('value', $data);
    }

    public function getSubKegiatanAktif(Request $request) {
        $query = SubKegiatanModel::query();
        $query->with(['kegiatan'])->when($request->search, fn($q) =>
        $q->where('nama_sub_kegiatan', 'like', "%{$request->search}%"))
        ->orWhere('kode_sub_kegiatan', 'like', "%{$request->search}%");
        $data = $query->limit(10)->get();
        return back()->with('value', $data);
    }

    public function saveData(Request $request) {
        $tahun = Cookie::get('tahun');
        $user = auth()->user();
        $idKabUser = UserKabupatenModel::where('id_user', $user->id)->pluck('id_kabupaten')->first();
        $idOpd = UserOpdModel::where('id_user', $user->id)->pluck('id_opd')->first();
        $role = $user->roles->pluck('name')[0];


        if ($role === 'super_admin') {
                $request['id_kabupaten'] = $request->input('id_kabupaten');
            } elseif ($role === 'admin_kab') {
                $request['id_kabupaten'] = $idKabUser;
            } elseif ($role === 'admin_opd') {
                $request['id_kabupaten'] = $idKabUser;
                $request['opd_id'] = $idOpd;
            }

        try {

            $request['tahun'] = $tahun;
            $validated = $request->validate([
                'id_kabupaten' => 'required',
                'opd_id' => 'required',
                'unit_id' => 'required',
                'id_sub_kegiatan' => 'required',
                'tahun' => TAHUN,
            ]);
            SubKegiatanAktifModel::create([
                'id_kabupaten' => $validated['id_kabupaten'],
                'id_opd' => $validated['opd_id'],
                'id_unit' => $validated['unit_id'],
                'id_sub_kegiatan' => $validated['id_sub_kegiatan'],
                'tahun' => $validated['tahun'],
            ]);
        // Simpan data ke database atau lakukan operasi lain
        return back()
            ->with('success', 'Data berhasil disimpan!')
            ->with('unitSubKegs', $this->getUnitSubKeg());
    } catch (\Exception $e) {
        dd($e);
        return back()->with('error', 'Data gagal disimpan!.');
    }
    }

    public function destroy(string $id)
    {
        try {
            $unitSubKeg = SubKegiatanAktifModel::findOrFail($id);
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
        $idKabUser = UserKabupatenModel::where('id_user', $user->id)->value('id_kabupaten');
        $idOpd = UserOpdModel::where('id_user', $user->id)->value('id_opd');

        $query = SubKegiatanAktifModel::with([
            'kabupaten',
            'opd',
            'unit',
            'sub_kegiatan.kegiatan'
        ])->where('tahun', Cookie::get('tahun'));

        $query->when($idOrName, function ($q) use ($idOrName) {
            $q->whereHas('sub_kegiatan', function ($q) use ($idOrName) {
                $q->where('nama_sub_kegiatan', 'like', "%{$idOrName}%")
                    ->orWhere('kode_sub_kegiatan', 'like', "%{$idOrName}%");
            });
        });

        switch ($role) {
            case 'admin_kab':
                $query->where('id_kabupaten', $idKabUser);
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
        $results = $query->paginate(10);
        $results->getCollection()->transform(function ($item, $key) use ($results) {
            $item->nomor_urut = $results->firstItem() + $key; // Nomor urut berdasarkan halaman
            return $item;
        });
        return $results;
    }

    public function getUnitSubkegiatan(string $idOrName = null ){
        $unitSubKeg = $this->getUnitSubKeg($idOrName);
        $unitRes = SubKegiatanAktifResource::collection($unitSubKeg);
        return back()->with('value', $unitRes);
    }
}
