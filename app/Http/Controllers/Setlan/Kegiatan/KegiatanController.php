<?php

namespace App\Http\Controllers\Setlan\Kegiatan;

use App\Http\Controllers\Controller;
use DB;
use Inertia\Inertia;
use App\Models\TahunModel;
use Illuminate\Http\Request;
use App\Models\KegiatanModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Builder;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $tahun = $request->input('tahun', Cookie::get('tahun'));

        $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
        if (!$tahun || !$tahun_aktif) {
            return back()->withErrors([
                'system' => SESI
            ]);
        }

        $query = KegiatanModel::where('tahun', $tahun_aktif->tahun_kegiatan)
        ->when($search, function(Builder $q) use ($search) {
            $q->where(function(Builder $query) use ($search) {
                $query->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('kode_kegiatan', 'like', "%{$search}%");
            });
        })
        ->orderBy('kode_kegiatan');
        // Pagination dengan tetap mempertahankan filter
        $kegiatans = $query->paginate($perPage)
            ->appends([
                'search' => $search,
                'tahun' => $tahun,
                'per_page' => $perPage
            ]);
        $tahun_aktif->pluck('tahun')->sortDesc()->values();

        return Inertia::render('Setlan/Kegiatan/KegiatanIndex', [
            'kegiatans' => $kegiatans,
            'tahun' => $tahun_aktif,
            'filters' => [
                'search' => $search,
                'tahun' => $tahun,
                'per_page' => $perPage
            ]
        ]);
    }

    public function store(Request $request)
{
    $tahun = Cookie::get('tahun');
    $tahun_aktif = TahunModel::where('tahun', $tahun)->first();
    if (!$tahun || !$tahun_aktif) {
        $response = back()->withErrors([
            'system' => SESI
        ]);
    } else {
        try {
            $request->id_kegiatan = null;
            $validated = $request->validate([
                            'kode_kegiatan' => [
                                    'required',
                                    'string',
                                    'regex:/^[0-9.]+$/',
                                        Rule::unique('kegiatan', 'kode_kegiatan')
                                        ->where(fn($query) => $query->where('tahun', $tahun_aktif->tahun_kegiatan))],
                            'nama_kegiatan' => 'required|string'
            ]);

            $validated['tahun'] = $tahun_aktif->tahun_kegiatan;

            $result = DB::transaction(fn() => KegiatanModel::create([
                    'kode_kegiatan' => $request->kode_kegiatan,
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'tahun' => $validated['tahun']
                ]));
            $transactionSuccess =
                $result->exists &&
                DB::transactionLevel() === 0 &&
                KegiatanModel::where('id_kegiatan', $result->id_kegiatan)->exists();
            if($transactionSuccess) {
                return redirect()->back()->with('success', 'Created successfully!');
            }
            $response = redirect()->back()->with('error', 'Gagal membuat kegiatan');
        } catch (\Throwable $th) {
            $response = redirect()->back()->with('error', 'Gagal membuat kegiatan: ' . $th->getMessage());
        }
    }
    return $response;
}


    public function update(Request $request, KegiatanModel $kegiatan)
{
    $tahun = Cookie::get('tahun');
    $tahunAktif = TahunModel::where('tahun', $tahun)->first();

    // Validasi tahun cookie
    if (!$tahun || !$tahunAktif) {
        return back()->withErrors(['system' => 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.']);
    }

    try {
        // Validasi input
        $validated = $request->validate([
            'id_kegiatan' => REQ_NUM,
            'kode_kegiatan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kegiatan')->where(fn($query) => $query->where('tahun', $tahunAktif->tahun_kegiatan))
                    ->ignore($request->id_kegiatan, 'id_kegiatan')
            ],
            'nama_kegiatan' => LONG_STR,
            'tahun' => [
                'nullable',
                'integer',
                'exists:tahun,tahun'
            ]
        ]);

        // Eksekusi database
        DB::transaction(fn() => $kegiatan->where('id_kegiatan', $request->id_kegiatan)->update($validated));

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
    public function destroy( KegiatanModel $kegiatan, $id)
    {
        try {
            DB::transaction(function () use ($kegiatan, $id) {
                $kegiatan->where('id_kegiatan', $id)->delete();
            });

            return redirect()->back()->with('success', 'Kegiatan berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus kegiatan: ' . $th->getMessage());
        }
    }
}
