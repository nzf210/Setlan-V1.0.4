<?php

namespace App\Http\Controllers\Setlan\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\AkunBelanjaAktifModel;
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
            'nama_akun' => 'nullable|string'
        ]);

        $tahun = Cookie::get('tahun');
        $tahun_aktif = TahunModel::where('tahun', $tahun)->first();

        if (!$tahun_aktif || !$tahun_aktif->tahun_akun) {
            return back()->withError('Tahun aktif tidak ditemukan atau belum memiliki data akun');
        } else {

            $query = AkunBelanjaModel::query()
                ->whereRaw('LENGTH(kode_akun)=17')
                ->where('tahun', $tahun_aktif->tahun_akun);

            if(!empty($validatedData['nama_akun'])){
                $this->applySearchFilters($query, $validatedData);
            }

            $accounts = $query->paginate(10)
                ->withQueryString()
                ->through(function ($item) {
                    $item->induk = $this->getIndukHierarki($item->kode_akun);
                    return $item;
                });
            return back()->with('value', $accounts);
        }
    }

    protected function applySearchFilters($query, array $filters)
    {
        if (!empty($filters['nama_akun'])) {
            $query->where('nama_akun', 'like', '%' . $filters['nama_akun'] . '%')
            ->orWhere('kode_akun', 'like', '%' . $filters['nama_akun'] . '%');
        }
    }

    private function getIndukHierarki($kode_akun)
        {
            $indukHierarki = [];
            // Loop untuk mengambil induk berdasarkan panjang ID akun
            while (strlen($kode_akun) > 1) {
                // Kurangi panjang ID akun sesuai dengan hierarki
                switch (strlen($kode_akun)) {
                    case 17: // R6 -> R5 (12 karakter)
                        $kode_akun = substr($kode_akun, 0, 12);
                        break;
                    case 12: // R5 -> R4 (9 karakter)
                        $kode_akun = substr($kode_akun, 0, 9);
                        break;
                    case 9: // R4 -> R3 (6 karakter)
                        $kode_akun = substr($kode_akun, 0, 6);
                        break;
                    case 6: // R3 -> R2 (3 karakter)
                        $kode_akun = substr($kode_akun, 0, 3);
                        break;
                    case 3: // R3 -> R2 (3 karakter)
                        $kode_akun = substr($kode_akun, 0, 1);
                        break;
                    default:
                        break;
                }

                $tahun = Cookie::get('tahun');
                $tahun_aktif = TahunModel::where('tahun', $tahun)->first()->tahun_akun;
                $induk = AkunBelanjaModel::where(['tahun' => $tahun_aktif , 'kode_akun'=> $kode_akun])->first();
                if ($induk) {
                    $indukHierarki[] = $induk;
                }
            }

        return $indukHierarki;
    }

public function GetAkunAktif(){
        $idKab = request()->cookie('id_kabupaten');
        $tahun = request()->cookie('tahun');
        if (!$idKab || !$tahun) {
            return redirect()->back()->withErrors('Parameter tahun atau kabupaten tidak valid');
        }
        $akunAktif = AkunBelanjaAktifModel::with(['akun', 'kabupaten'])
            ->where('id_kabupaten', $idKab)
            ->where('tahun', $tahun)
            ->filtered()
            ->paginate(25);
        $dt = $akunAktif->getCollection()->transform(function ($item) {
                $akun = $item->akun;
                $kabupaten = $item->kabupaten;

                return (object) [
                    'id_akun_aktif' => $item->id_akun_aktif,
                    'original' => $item,
                    'induk' => $this->getIndukHierarki($akun->kode_akun),
                    'kode_akun' => $akun->kode_akun,
                    'id_akun' => $akun->id_akun,
                    'nama_akun' => $akun->nama_akun,
                    'nama_kabupaten' => $kabupaten->nama_kabupaten,
                    'tahun' => $item->tahun
                ];
            });
        return back()->with(['value'=> $dt]);
}

public function CreateAkunAktif(Request $request)
    {
        $idKab = null;
        $tahun = null;
        $error = null;

        if (request()->hasCookie('id_kabupaten')) {
            $idKab = request()->cookie('id_kabupaten');
        } else {
            $error = 'id Kabupaten belum di set.';
        }

        if (request()->hasCookie('tahun')) {
            $tahun = request()->cookie('tahun');
        } else {
            $error = 'tahun belum di set.';
        }

        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        try {
        $akuns = $request->input('akuns', []);

        $akunsWithIdKab = array_map(function($akun) use ($idKab,$tahun) {
                $akun['id_kabupaten'] = $idKab;
                $akun['tahun'] = $tahun;
                return $akun;
                }, $akuns);
        $modifiedRequest = new Request(['akuns' => $akunsWithIdKab]);

        $validatedData = $modifiedRequest->validate([
            'akuns' => 'required|array',
            'akuns.*.id_kabupaten' => REQ_NUM,
            'akuns.*.tahun' => REQ_NUM,
            'akuns.*.id_akun' => REQ_NUM,
        ]);
            DB::transaction(function () use ($validatedData) {
                $succes = 0;
                foreach ($validatedData['akuns'] as $akunData) {
                    AkunBelanjaAktifModel::create($akunData);
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
            dd($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambah daftar akun.'. $e->getMessage())->withInput();
        }
}
public function DeleteAkunAktif($id) {
    try {
        $id_akun_aktif = $id;
        $record = AkunBelanjaAktifModel::findOrFail($id_akun_aktif);
        $record->delete();
        return redirect()->back()->with('success', 'Berhasil Menghapus Akun Aktif.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
    }
}

}
