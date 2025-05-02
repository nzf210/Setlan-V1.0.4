<?php

namespace App\Http\Controllers\Setlan;

use App\Http\Controllers\Controller;
use App\Models\MKab;
use App\Models\MOpd;
use App\Models\MUnit;
use App\Models\UserKabupaten;
use App\Models\UserOpd;
use App\Models\UserUnit;
use App\Models\Year;
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

        $idKab = Cookie::get('id_kab');
        $idOpd = Cookie::get('id_opd');
        $idUnit = Cookie::get('id_unit');

        $namaKab = MKab::where('id_kab', $idKab)->get(['nama_kab']);
        $namaOpd = MOpd::where('id_opd', $idOpd)->get(['nama_opd']);
        $namaUnit = MUnit::where('id_unit', $idUnit)->get(['nama_unit']);
        return Inertia::render('Setlan/Dashboard', [
            'user' => Auth::user(),
            'year' => Cookie::get('year') ?? date('Y'),
            'role' => Auth::user()->getRoleNames()[0],
            'nama_kab' => $namaKab->first()->nama_kab,
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
            'kab' => $list_data['kab'],
            'opd' => $list_data['opd'],
            'unit' => $list_data['unit'],
            'tahun' => $this->getTahun(),
        ]);

    }

    public function getListOpd(Request $request)
    {
        $list_opd = MOpd::where('id_kab', $request->id)->get(['id_opd', 'nama_opd'])->toArray();
        return redirect()->back()->with('opd', $list_opd);
    }

    public function getListUnit(Request $request)
    {
        $list_unit = MUnit::where('id_opd', $request->id)->get(['id_unit', 'nama_unit'])->toArray();
        return redirect()->back()->with('unit', $list_unit);
    }


    private function getTahun()
    {
        return Year::select('year')
            ->orderBy('year', 'desc')
            ->get()
            ->map(fn($item) => [
                'value' => (string) $item->year,
                'label' => (string) $item->year,
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
        'kab' => MKab::all()->toArray(),
        'opd' => [],
        'unit' => [],
    ];
}

private function handleAdminKabRole($user)
{
    $id_kab = UserKabupaten::where('id_user', $user->id)->pluck('id_kab')->first();
    $list_kab = MKab::where('id_kab', $id_kab)->get()->toArray();
    $list_opd = MOpd::where('id_kab', $id_kab)->get(['id_opd', 'nama_opd'])->toArray();

    Cookie::queue("id_kab", $id_kab, $this->minutes);

    return [
        'kab' => $list_kab,
        'opd' => $list_opd,
        'unit' => [],
    ];
}

private function handleAdminOpdRole($user)
{
    $id_opd = UserOpd::where('id_user', $user->id)->pluck('id_opd')->first();
    $list_opd = MOpd::where('id_opd', $id_opd)->get()->toArray();
    $list_kab = MKab::where('id_kab', $list_opd[0]['id_kab'])->get()->toArray();
    $list_unit = MUnit::where('id_opd', $id_opd)->get(['nama_unit', 'id_unit'])->toArray();

    Cookie::queue("id_kab", $list_kab[0]['id_kab'], $this->minutes);
    Cookie::queue("id_opd", $list_opd[0]['id_opd'], $this->minutes);

    return [
        'kab' => $list_kab,
        'opd' => $list_opd,
        'unit' => $list_unit,
    ];
}

private function handleDefaultRole($user)
{
    $id_unit = UserUnit::where('id_user', $user->id)->pluck('id_unit')->toArray();
    $list_unit = MUnit::whereIn('id_unit', $id_unit)->get(['id_unit', 'nama_unit'])->toArray();
    $list_opd = MOpd::where('id_opd', $id_unit[0])->get(['id_opd', 'nama_opd', 'id_kab'])->toArray();
    $id_kab = MKab::where('id_kab', $list_opd[0]['id_kab'])->get(['id_kab', 'nama_kab'])->toArray();

    Cookie::queue("id_kab", $id_kab[0]['id_kab'], $this->minutes);
    Cookie::queue("id_opd", $list_opd[0]['id_opd'], $this->minutes);

    // Hapus 'id_opd' dari setiap item di $list_unit
    $list_unit = array_map(function ($item) {
        unset($item['id_opd']);
        return $item;
    }, $list_unit);

    return [
        'kab' => $id_kab,
        'opd' => $list_opd,
        'unit' => $list_unit,
    ];
}
}
