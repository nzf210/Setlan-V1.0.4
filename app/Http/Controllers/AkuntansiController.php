<?php

namespace App\Http\Controllers;

use App\Models\AkunAktifModel;
use App\Models\AkunBelanjaModel;
use App\Models\TahunModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AkuntansiController extends Controller
{
    public function index()
    {
        return Inertia::render('Setlan/Akuntansi/Akun');
    }
    public function Akuntansi()
    {
        return Inertia::render('Setlan/Akuntansi/Akun');
    }
    public function Kebijakan()
    {
        return Inertia::render('Setlan/Akuntansi/Kebijakan');
    }

    public function getAkunData(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'nullable|string',
            'id_akun' => 'nullable|string'
        ]);

        $tahun = Cookie::get('year');
        $tahun_aktif = TahunModel::where('year', $tahun)->first();

        if (!$tahun_aktif || !$tahun_aktif->akun) {
            return back()->withError('Tahun aktif tidak ditemukan atau belum memiliki data akun');
        } else {

            $query = AkunBelanjaModel::query()
            ->whereRaw('LENGTH(id_akun) = 17')
            ->where('tahun', $tahun_aktif->akun);

            $this->applySearchFilters($query, $validatedData);

            $accounts = $query->paginate(10)
            ->withQueryString()
            ->through(function ($item) {
                $item->induk = $this->getIndukHierarki($item->id_akun);
                return $item;
            });
            return back()->with('value', $accounts);
        }
    }

    protected function applySearchFilters($query, array $filters)
    {
        if (!empty($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }

        if (!empty($filters['id_akun'])) {
            $query->where('id_akun', 'like', '%' . $filters['id_akun'] . '%');
        }
    }

    private function getIndukHierarki($id_akun,$now=true)
        {
            $indukHierarki = [];
            // Loop untuk mengambil induk berdasarkan panjang ID akun
            while (strlen($id_akun) > 1) {
                // Kurangi panjang ID akun sesuai dengan hierarki
                switch (strlen($id_akun)) {
                    case 17: // R6 -> R5 (12 karakter)
                        $id_akun = substr($id_akun, 0, 12);
                        break;
                    case 12: // R5 -> R4 (9 karakter)
                        $id_akun = substr($id_akun, 0, 9);
                        break;
                    case 9: // R4 -> R3 (6 karakter)
                        $id_akun = substr($id_akun, 0, 6);
                        break;
                    case 6: // R3 -> R2 (3 karakter)
                        $id_akun = substr($id_akun, 0, 3);
                        break;
                    case 3: // R3 -> R2 (3 karakter)
                        $id_akun = substr($id_akun, 0, 1);
                        break;
                    default:
                        break;
                }

                $tahun = Cookie::get('year');
                $tahun_aktif = $now ? TahunModel::where('year', $tahun)->first()->akun : $tahun;
                $induk = AkunBelanjaModel::where(['tahun' => $tahun_aktif , 'id_akun'=> $id_akun])->first();
                if ($induk) {
                    $indukHierarki[] = $induk;
                }
            }

        return $indukHierarki;
    }

public function GetAkunAktif(){
        $idKab = request()->cookie('id_kab');
        $tahun = request()->cookie('year');
        if (!$idKab || !$tahun) {
            return redirect()->back()->withErrors('Parameter tahun atau kabupaten tidak valid');
        }

        $data = AkunAktifModel::with(['akun','mKab'])->where(['id_kab' => $idKab, 'tahun' => $tahun]);
        $dt= $data->filtered()->paginate(25)->withQueryString();

        $dt->getCollection()->transform(function ($item) {
            $item->induk = $this->getIndukHierarki($item->id_akun,false);
            return $item;
        });
        return back()->with(['value'=> $dt]);
}

public function CreateAkunAktif(Request $request)
    {
        $idKab = null;
        $tahun = null;
        $error = null;

        if (request()->hasCookie('id_kab')) {
            $idKab = request()->cookie('id_kab');
        } else {
            $error = 'id Kabupaten belum di set.';
        }

        if (request()->hasCookie('year')) {
            $tahun = request()->cookie('year');
        } else {
            $error = 'tahun belum di set.';
        }

        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        try {
        $akuns = $request->input('akuns', []);
        $akunsWithIdKab = array_map(function($akun) use ($idKab,$tahun) {
            $akun['id_kab'] = $idKab;
            $akun['tahun'] = $tahun;
            $akun['ids'] = $akun['id'];
            return $akun;
            }, $akuns);
            $modifiedRequest = new Request(['akuns' => $akunsWithIdKab]);

            $validatedData = $modifiedRequest->validate([
                'akuns' => 'required|array',
                'akuns.*.ids' => 'required|numeric',
                'akuns.*.id_akun' => 'required|string|max:17',
                'akuns.*.id_kab' => 'required|string|max:5',
                'akuns.*.nama' => 'required|string|max:255',
                'akuns.*.tahun' => 'required|numeric',
            ]);
            DB::transaction(function () use ($validatedData) {
                $succes = 0;
                foreach ($validatedData['akuns'] as $akunData) {
                    AkunAktifModel::create($akunData);
                    $succes++;
                }
                if ($succes > 0) {
                    return redirect()->back()->with('success', 'Berhasil menambah daftar akun.');
                }
                return redirect()->back()->with('error', 'Gagal menambah daftar akun.');
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menambah daftar akun.')->withInput();
        }
}

public function DeleteAkunAktif(Request $request, $id){
        AkunAktifModel::where('id', $id)->firstOrFail()->delete();
        return redirect()->back()->with('success', 'Berhasil Menghapus Akun Aktif.');
    }

}
