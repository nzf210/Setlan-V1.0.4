<?php

namespace App\Http\Controllers;

use App\Models\MKeg;
use App\Models\Year;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

define('SESI', 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.');
class MKegController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $tahun = $request->input('year', Cookie::get('year'));

        // Validasi tahun
        $tahun_aktif = Year::where('year', $tahun)->first();
        if (!$tahun || !$tahun_aktif) {
            return back()->withErrors([
                'system' => SESI
            ]);
        }

        // Query dasar
        $query = MKeg::where('tahun', $tahun_aktif->keg)
        ->when($search, function(Builder $q) use ($search) {
            $q->where(function(Builder $query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('id_keg', 'like', "%{$search}%");
            });
        })
        ->orderBy('id_keg');

        // Pagination dengan tetap mempertahankan filter
        $kegiatans = $query->paginate($perPage)
            ->appends([
                'search' => $search,
                'year' => $tahun,
                'per_page' => $perPage
            ]);
        $years = Year::pluck('year')->sortDesc()->values();

        return Inertia::render('Setlan/Kegiatan/KegiatanIndex', [
            'kegiatans' => $kegiatans,
            'years' => $years,
            'filters' => [
                'search' => $search,
                'year' => $tahun,
                'per_page' => $perPage
            ]
        ]);
    }

    public function store(Request $request)
{
    $tahun = Cookie::get('year');
    $tahun_aktif = Year::where('year', $tahun)->first();
    if (!$tahun || !$tahun_aktif) {
        $response = back()->withErrors([
            'system' => SESI
        ]);
    } else {
        try {
                $validated = $request->validate([
                        'id_keg' => 'required|string|max:255',
                        'nama' => 'required|string',
                        'tahun' => 'nullable|exists:years,year'
                    ]);
                $validated['tahun'] = $tahun_aktif->keg;
                                DB::transaction(function () use ($request, $validated) {
                            MKeg::create([
                                'id_keg' => $request->id_keg,
                                'nama' => $request->nama,
                                'tahun' => $validated['tahun']
                            ]);
                        });
            $response = redirect()->back()->with('success', 'Kegiatan berhasil dibuat');
        } catch (\Throwable $th) {
            $response = redirect()->back()->with('error', 'Gagal membuat kegiatan: ' . $th->getMessage());
        }
    }
    return $response;
}


    public function update(Request $request, MKeg $kegiatan)
{
    $tahun = Cookie::get('year');
    $tahunAktif = Year::where('year', $tahun)->first();

    // Validasi tahun cookie
    if (!$tahun || !$tahunAktif) {
        return back()->withErrors(['system' => 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.']);
    }

    try {
        // Validasi input
        $validated = $request->validate([
            'id_keg' => [
                'required',
                'string',
                'max:255',
                Rule::unique('m_kegs')->where(fn($query) => $query->where('tahun', $tahunAktif->year))
                    ->ignore($request->id)
            ],
            'nama' => 'required|string|max:65535',
            'tahun' => [
                'nullable',
                'integer',
                'exists:years,year',
                Rule::unique('m_kegs')->where(fn($query) => $query->where('id_keg', $request->id_keg))
                    ->ignore($request->id)
            ]
        ]);

        // Eksekusi database
        DB::transaction(fn() => $kegiatan->where('id', $request->id)->update($validated));

        return redirect()->back()->with('success', 'Kegiatan berhasil diperbarui');

    } catch (\Exception $e) {
        // Handle semua jenis exception
        $errorMessage = $e instanceof \Illuminate\Validation\ValidationException
        ? 'Validasi gagal: ' . is_array($e->getMessage())
        : 'Gagal memperbarui kegiatan: ' . $e->getMessage();

        return redirect()->back()
            ->with('error', $errorMessage)
            ->withInput();
    }
}
    public function destroy( MKeg $kegiatan, $id)
    {
        try {
            DB::transaction(function () use ($kegiatan, $id) {
                $kegiatan->where('id', $id)->delete();
            });

            return redirect()->back()->with('success', 'Kegiatan berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus kegiatan: ' . $th->getMessage());
        }
    }
}
