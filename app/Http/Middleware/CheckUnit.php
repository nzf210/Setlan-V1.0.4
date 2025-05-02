<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserKabupaten;
use App\Models\UserOpd;
use App\Models\UserUnit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckUnit
{
    public function handle(Request $request, Closure $next): Response
    {
       // Get the logged-in user
    // $user = Auth::user();

    // $list_kab = UserKabupaten::where('id_user', $user->id)->pluck('id_kab')->toArray();
    // $list_opd = UserOpd::where('id_user', $user->id)->pluck('id_opd')->toArray();
    // $list_unit = UserUnit::where('id_user', $user->id)->pluck('id_unit')->toArray();
    // dd($list_kab, $list_opd, $list_unit, $user->getAuthIdentifier());
        return $next($request);
    }
}
