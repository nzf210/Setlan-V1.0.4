<?php

namespace App\Http\Controllers;

use App\Http\Resources\BarangResource;
use App\Http\Resources\CategoryResource;
use App\Models\BarangModel;
use App\Models\KodeBarangModel;
use App\Models\MutasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

define("STRREQ255", 'required|string|max:255');
define("NUMREQGT0", 'required|numeric|gt:0');
define("DTREQ", 'required|date');
define("REQNUM", 'required|numeric');
define("PILIH_UNIT", 'Pilih unit terlebih dahulu.');
class BarangController extends Controller
{
    public function index()
    {
        return Inertia::render("Setlan/Barang/Index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        return Inertia::render('Setlan/Barang/Index');
    }

    public function masterBarang()
{
    try {
        if (!request()->hasCookie('id_kabupaten') || !request()->hasCookie('id_unit') || !request()->hasCookie('id_opd')) {
            return back()->withErrors(PILIH_UNIT)->withInput();
        }

        $idKab = request()->cookie('id_kabupaten');
        $tahun = request()->cookie('tahun');

        $hargaFrom = request('harga.from', 0); // Default 0 jika tidak ada
        $hargaTo = request('harga.to', 100000000); // Default 100,000,000 jika tidak ada
        $perPage = request('per_page', 10); // Default 10 jika tidak ada

        // Query barang dasar
        $barang = BarangModel::with(['category', 'akun', 'satuan'])
            ->where('id_kabupaten', $idKab)
            ->where('tahun', $tahun);

        // Cek apakah nilai filter adalah default
        $isDefaultFilter = $hargaFrom == 10000 && $hargaTo == 100000000;
        // Tambahkan filter harga hanya jika bukan nilai default
        if (!$isDefaultFilter) {
            $barang->whereBetween('harga', [$hargaFrom, $hargaTo]);
        }

        // Paginasi hasil query
        $barangs = $barang->filtered()->paginate($perPage == -1 ? 10000000 : $perPage)->withQueryString();
        // Ambil data kategori barang
        $category = KodeBarangModel::whereRaw('LENGTH(id_kd_barang) = 20')->paginate(10)->withQueryString();

        // Render halaman dengan Inertia
        return Inertia::render('Setlan/Barang/MasterBarang', [
            'barangs' => fn() => BarangResource::collection($barangs),
            'categories' => fn() => CategoryResource::collection($category),
            'breadcrumb' => [
                ['name' => 'Dashboard', 'url' => route('setlan.dashboard')],
                ['name' => 'Master barang', 'url' => '/Setlan/Barang/MasterBarang'],
            ],
        ]);
    } catch (\Throwable $th) {
        return back()->withErrors('error', PILIH_UNIT)->withInput();
    }
}
    public function masterBarangAdd(Request $request)
    {
        $response = null;
        try {
            if (request()->hasCookie('id_kabupaten') || request()->hasCookie('id_unit') || request()->hasCookie('id_opd')) {
                $idKab = request()->cookie('id_kabupaten');
                $idUnit = request()->cookie('id_unit');
                $idOpd = request()->cookie('id_opd');
                $year = request()->cookie('tahun');
            } else {
                $response = back()->withErrors(PILIH_UNIT)->withInput();
            }

            if($idKab == null || $idUnit == null || $idOpd == null){
                $response = back()->withErrors('error',PILIH_UNIT)->withInput();
            }

            $request->validate([
                'nama_barang' => STRREQ255,
                'merek' => STRREQ255,
                'id_kd_barang' => NUMREQGT0,
                'satuan_id' => REQNUM,
                'harga' => NUMREQGT0,
                'type' => 'nullable|string|max:255',
                'id_akun' => NUMREQGT0,
            ]);

            $id = $this->generateCustomId();
            $barang = new BarangModel();
            $barang->id_barang = $id;
            $barang->nama_barang = $request->nama_barang;
            $barang->merek = $request->merek;
            $barang->id_kd_barang = $request->id_kd_barang;
            $barang->satuan_id = $request->satuan_id;
            $barang->harga = $request->harga;
            $barang->type = $request->type;
            $barang->created_by = Auth::user()->id;
            $barang->id_kabupaten = $idKab;
            $barang->id_unit = $idUnit;
            $barang->id_opd = $idOpd;
            $barang->tahun = $year;
            $barang->id_akun = $request->id_akun;
            $hsl = $barang->save();

            if($request->is_add_draft === '1' && $hsl){
                $mutasi = new MutasiModel();
                $mutasi->id_barang = $id;
                $mutasi->id_unit = $idUnit;
                $mutasi->id_opd = $idOpd;
                $mutasi->harga = $request->harga;
                $mutasi->id_kabupaten = $idKab;
                $mutasi->tgl_beli = date('Y-m-d');
                $mutasi->tahun = $year;
                $mutasi->created_by = Auth::user()->id;
                $mutasi->updated_by = Auth::user()->id;
                $mutasi->save();
            }

            $response = back()->with('success', 'Berhasil menambahkan data.');
            } catch (ValidationException $e) {
                $response = back()->withErrors('error', PILIH_UNIT)->withInput();
        }
        return $response;
    }

    public function masterBarangEdit(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|string|max:6',
            'nama_barang' => 'required|max:255',
            'merek' => 'required|string|max:255',
            'id_kd_barang' => NUMREQGT0,
            'satuan_id' => REQNUM,
            'harga' => NUMREQGT0,
            'type' => 'nullable|string|max:255',
            'id_akun' => NUMREQGT0,
        ]);

        $id_barang = $request->id_barang;
        $barang = BarangModel::where("id_barang", $id_barang)->firstOrFail();
        $barang->nama_barang = $request->nama_barang;
        $barang->merek = $request->merek;
        $barang->id_kd_barang = $request->id_kd_barang;
        $barang->satuan_id = $request->satuan_id;
        $barang->harga = $request->harga;
        $barang->type = $request->type;
        $barang->id_akun = $request->id_akun;
        $barang->updated_by = Auth::user()->id;
        $barang->save();
        return redirect()->route('setlan.barang.master')->with('success', 'Berhasil Mengubah Barang.');
    }

    public function barangMasterDelete(Request $request, $id)
    {
        BarangModel::where('id_barang', $id)->firstOrFail()->delete();
        return redirect()->route('setlan.barang.master')->with('success', 'Berhasil Menghapus Barang.');
    }

    public function barangMasuk(BarangModel $barang)
    {
        return Inertia::render('Setlan/Barang/BarangMasuk');
    }

    public function barangKeluar(BarangModel $barang)
    {
        return Inertia::render('Setlan/Barang/BarangKeluar');
    }

    private function generateCustomId()
    {
        $alphabet = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
        $numbers = substr(str_shuffle('0123456789'), 0, 3);
        return $alphabet . $numbers;
    }

}
