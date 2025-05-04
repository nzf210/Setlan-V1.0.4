<?php

namespace App\Http\Controllers\Setlan;

use App\Http\Controllers\Controller;
use App\Models\KabupatenModel;
use App\Models\OpdModel;
use App\Models\TahunModel;
use App\Models\UnitModel;
use App\Models\UserKabupatenModel;
use App\Models\UserOpdModel;
use App\Models\UserUnitModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

define('SETIDX', 'Setlan/ChooseUnit/Index');
class DashboardController extends Controller
{
    protected $minutes = 60 * 24 * 30; // 30 days

    public function index()
    {
        try {

        $idKab = Cookie::get('id_kabupaten');
        $idOpd = Cookie::get('id_opd');
        $idUnit = Cookie::get('id_unit');

        $namaKab = KabupatenModel::where('id_kabupaten', $idKab)->get(['nama_kabupaten']);
        $namaOpd = OpdModel::where('id_opd', $idOpd)->get(['nama_opd']);
        $namaUnit = UnitModel::where('id_unit', $idUnit)->get(['nama_unit']);
        return Inertia::render('Setlan/Dashboard', [
            'user' => Auth::user(),
            'tahun' => Cookie::get('tahun') ?? date('Y'),
            'role' => Auth::user()->getRoleNames()[0],
            'nama_kabupaten' => $namaKab->first()->nama_kabupaten,
            'nama_opd' => $namaOpd->first()->nama_opd,
            'nama_unit' => $namaUnit->first()->nama_unit,
        ]);

    } catch (\Throwable $th) {
        Log::info($th);
    }

    }

    public function unit()
    {

        $list_data = $this->checkRoleLogin();
        return Inertia::render(SETIDX, [
            'user' => Auth::user(),
            'kabupaten' => $list_data['kabupaten'],
            'opd' => $list_data['opd'],
            'unit' => $list_data['unit'],
            'tahun' => $this->getTahun(),
        ]);

    }

    public function getListOpd(Request $request)
    {
        $list_opd = OpdModel::where('id_kabupaten', $request->id)->get(['id_opd', 'nama_opd'])->toArray();
        return redirect()->back()->with('opd', $list_opd);
    }

    public function getListUnit(Request $request)
    {
        $list_unit = UnitModel::where('id_opd', $request->id)->get(['id_unit', 'nama_unit'])->toArray();
        return redirect()->back()->with('unit', $list_unit);
    }


    private function getTahun()
    {
        return TahunModel::select('tahun')
            ->orderBy('tahun', 'desc')
            ->get()
            ->map(fn($item) => [
                'value' => (string) $item->tahun,
                'label' => (string) $item->tahun,
            ]);
    }

    private function checkRoleLogin()
{
    $user = Auth::user();
    $role = $user->getRoleNames()[0];
    return match ($role) {
        'super_admin' => $this->handleSuperAdminRole(),
        'admin_kab' => $this->handleAdminKabRole($user),
        'admin_opd' => $this->handleAdminOpdRole($user),
        default => $this->handleDefaultRole($user),
    };
}

private function handleSuperAdminRole()
{
    return [
        'kabupaten' => KabupatenModel::all()->toArray(),
        'opd' => [],
        'unit' => [],
    ];
}

private function handleAdminKabRole($user)
{
    $id_kabupaten = UserKabupatenModel::where('id_user', $user->id)->pluck('id_kabupaten')->first();
    $list_kabupaten = KabupatenModel::where('id_kabupaten', $id_kabupaten)->get()->toArray();
    $list_opd = OpdModel::where('id_kabupaten', $id_kabupaten)->get(['id_opd', 'nama_opd'])->toArray();

    Cookie::queue("id_kabupaten", $id_kabupaten, $this->minutes);

    return [
        'kabupaten' => $list_kabupaten,
        'opd' => $list_opd,
        'unit' => [],
    ];
}

private function handleAdminOpdRole($user)
{
    $id_opd = UserOpdModel::where('id_user', $user->id)->pluck('id_opd')->first();
    $list_opd = OpdModel::where('id_opd', $id_opd)->get()->toArray();
    $list_kab = KabupatenModel::where('id_kabupaten', $list_opd[0]['id_kabupaten'])->get()->toArray();
    $list_unit = UnitModel::where('id_opd', $id_opd)->get(['nama_unit', 'id_unit'])->toArray();

    Cookie::queue("id_kabupaten", $list_kab[0]['id_kabupaten'], $this->minutes);
    Cookie::queue("id_opd", $list_opd[0]['id_opd'], $this->minutes);

    return [
        'kabupaten' => $list_kab,
        'opd' => $list_opd,
        'unit' => $list_unit,
    ];
}

private function handleDefaultRole($user)
{
    $id_unit = UserUnitModel::where('id_user', $user->id)->pluck('id_unit')->toArray();
    $list_unit = UnitModel::whereIn('id_unit', $id_unit)->get(['id_unit', 'nama_unit'])->toArray();
    $list_opd = OpdModel::where('id_opd', $id_unit[0])->get(['id_opd', 'nama_opd', 'id_kabupaten'])->toArray();
    $id_kab = KabupatenModel::where('id_kabupaten', $list_opd[0]['id_kabupaten'])->get(['id_kabupaten', 'nama_kabupaten'])->toArray();

    Cookie::queue("id_kabupaten", $id_kab[0]['id_kabupaten'], $this->minutes);
    Cookie::queue("id_opd", $list_opd[0]['id_opd'], $this->minutes);

    // Hapus 'id_opd' dari setiap item di $list_unit
    $list_unit = array_map(function ($item) {
        unset($item['id_opd']);
        return $item;
    }, $list_unit);

    return [
        'kabupaten' => $id_kab,
        'opd' => $list_opd,
        'unit' => $list_unit,
    ];
}
}
