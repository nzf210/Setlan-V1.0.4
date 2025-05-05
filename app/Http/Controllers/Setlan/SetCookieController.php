<?php

namespace App\Http\Controllers\Setlan;

use App\Http\Controllers\Controller;
use App\Models\OpdModel;
use App\Models\UnitModel;
use App\Models\UserKabupatenModel;
use App\Models\UserOpdModel;
use App\Models\UserUnitModel;
use Closure;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class SetCookieController extends Controller
{
    protected $minutes = 60 * 24 * 30; // 30 days
    public function setCookie(Request $request)
    {

        if (!Auth::check()) {
            return Redirect::back()->with(['error' => 'Login Dulu.'], 400);
        }
        switch (Auth::user()->getRoleNames()[0]) {
            case 'super_admin':
                $request->validate([
                    'id_kabupaten' =>  REQ_NUM,
                    'id_opd' => REQ_NUM,
                    'id_unit' => REQ_NUM,
                    'tahun' => TAHUN,
                ]);
                Cookie::queue("id_kabupaten", $request->id_kabupaten, $this->minutes);
                Cookie::queue("id_opd", $request->id_opd, $this->minutes);
                Cookie::queue("id_unit", $request->id_unit, $this->minutes);
                Cookie::queue("tahun", $request->tahun, $this->minutes);
            break;
            case 'admin_kab':
                $request->validate([
                'id_opd' => REQ_NUM,
                'id_unit' => REQ_NUM,
                'tahun' => TAHUN,
                ]);
                Cookie::queue("id_opd", $request->id_opd, $this->minutes);
                Cookie::queue("id_unit", $request->id_unit, $this->minutes);
                Cookie::queue("tahun", $request->tahun, $this->minutes);
            break;
            default:
                $request->validate([
                'id_unit' => REQ_NUM,
                'tahun' => TAHUN,
                ]);
                Cookie::queue("id_unit", $request->id_unit, $this->minutes);
                Cookie::queue("tahun", $request->tahun, $this->minutes);
            break;
        }
        return Redirect::back()->with('success', "Set id cookie");
    }

    public function deleteCookie(Request $request)
    {
        if ($request->id_kabupaten == null && $request->id_opd == null && $request->id_unit == null) {
            return Redirect::back()->with(['error' => 'Pilih Unit Dengan Benar.'], 400);
        }

        if ($request->id_kabupaten) {
            Cookie::queue(Cookie::forget("id_kabupaten"));
        }

        if ($request->id_opd) {
            Cookie::queue(Cookie::forget("id_opd"));
        }

        if ($request->id_unit) {
            Cookie::queue(Cookie::forget("id_unit"));
        }

        return Redirect::back()->with('success', "delete cookie");
    }

    public function getCookie($type)
    {
        $cookieName = "id_$type";
        $cookieValue = Cookie::get($cookieName);

        if ($cookieValue) {
            return Redirect::back()->with(['success' => "$type", 'value' => $cookieValue]);
        } else {
            return Redirect::back()->with(['error' => "$type gagal memuat"], 400);
        }
    }

    public function checkStatus()
    {
        try {
            $idKab = Cookie::get('id_kabupaten');
            $idOpd = Cookie::get('id_opd');
            $idUnit = Cookie::get('id_unit');

            $idUser = Auth::user()->id;
            /**
             * @var mixed
                $userRole = Auth::user()->getRoleNames()[0];
             */

            $unitExistsInOpd = UnitModel::where('id_unit', $idUnit)
                                    ->where('id_opd', $idOpd)
                                    ->exists();

            $opdExistsInKab = OpdModel::where('id_opd', $idOpd)
                                    ->where('id_kabupaten', $idKab)
                                    ->exists();

            $result = $this->validateUnitRelations($unitExistsInOpd, $opdExistsInKab);
            if (!$result['success']) {
                return redirect()->back()->with('error', $result['message']);
            }

            $isExistKab = UserKabupatenModel::where('id_kabupaten', $idKab)->where('id_user', $idUser)->first();
            $isExistOpd = UserOpdModel::where('id_opd', $idOpd)->where('id_user', $idUser)->first();
            $isExistUnit = UserUnitModel::where('id_unit', $idUnit)->where('id_user', $idUser)->first();

            $result = $this->validateUserRelations($isExistKab, $isExistOpd, $isExistUnit);

            if (!$result['success']) {
                return redirect()->back()->with('error', $result['message']);
            }
            return redirect()->back()->with('success', 'Berhasil Masuk ...');
        } catch (\Exception $e) {
            // Handle exception
            dd($e->getMessage());
        }
}

private function validateUnitRelations($unitExistsInOpd, $opdExistsInKab)
{
    if (!$unitExistsInOpd || !$opdExistsInKab) {
        return ['success' => false, 'message' => 'Kesalahan Pemilihan Unit'];
    }
    return ['success' => true];
}

private function validateUserRelations($isExistKab, $isExistOpd, $isExistUnit)
{
    // Logika berdasarkan role
    switch (Auth::user()->getRoleNames()[0]) {
        case 'super_admin':
            break;
        case 'admin_kab':
            // Wajib memiliki relasi dengan kabupaten atau opd
            if (!$isExistKab && !$isExistOpd ) {
                return ['success' => false, 'message' => 'Anda tidak memiliki akses ke kabupaten ini'];
            }
            break;

        default:
            if ( !$isExistUnit && !$isExistKab) {
                return ['success' => false, 'message' => 'Anda tidak memiliki akses ke unit ini'];
            }
            break;
        }
        return ['success' => true];

    }
}
