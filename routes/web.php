<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Setlan\StokController;
use App\Http\Controllers\Setlan\DashboardController;
use App\Http\Controllers\Setlan\SetCookieController;
use App\Http\Controllers\Setlan\Master\TahunController;
use App\Http\Controllers\Setlan\Barang\BarangController;
use App\Http\Controllers\Setlan\Master\SatuanController;
use App\Http\Controllers\Setlan\Master\GetDataController;
use App\Http\Controllers\Setlan\Kegiatan\KegiatanController;
use App\Http\Controllers\Setlan\Barang\KodeBarangController;
use App\Http\Controllers\Setlan\Persediaan\MutasiController;
use App\Http\Controllers\Setlan\Akuntansi\AkuntansiController;
use App\Http\Controllers\Setlan\Kegiatan\SubkegiatanController;
use App\Http\Controllers\Setlan\Kegiatan\SubKegiatanAktifController;

define('PROFILE_ROUTE', 'profile');
define('LOGIN', 'login');
define('BRGMASTER', '/barang/master');
define('IDBARANG', '/{id}'.'/{id_barang}');

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has(LOGIN),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware(['cors']);

Route::middleware('auth')->group(function () {
    Route::get('/', fn () =>   redirect()->route('setlan.pilihUnit'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'cors'])->prefix('setlan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('setlan.dashboard');
    Route::get('/pilihUnit', [DashboardController::class, 'unit'])->name('setlan.pilihUnit');
    Route::get('/get-cookie', [SetCookieController::class, 'checkStatus'])->name('setlan.getCookieStatus');
    Route::post('/set-cookie', [SetCookieController::class, 'setCookie'])->name('setlan.setCookie');
    Route::post('/delete-cookie}', [SetCookieController::class, 'deleteCookie'])->name('setlan.deleteCookie');
    Route::get('/get-cookie/{type}', [SetCookieController::class, 'getCookie'])->name('setlan.getCookie');
    Route::get('/check-cookie', [SetCookieController::class, 'checkStatus'])->name('setlan.checkCookie');
    Route::get('/setlan/{id}/getListOpd', [DashboardController::class, 'getListOpd'])->name('setlan.getListOpd');
    Route::get('/setlan/{id}/getListUnit', [DashboardController::class, 'getListUnit'])->name('setlan.getListUnit');
});

Route::get('/about', fn () => Inertia::render('Dashboard'))->name('about');

Route::middleware(['auth', 'cors'])->prefix('setlan')->group(function () {
    Route::get(PROFILE_ROUTE, [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch(PROFILE_ROUTE, [ProfileController::class, 'update'])->name('profile.update');
    Route::delete(PROFILE_ROUTE, [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/barang', [BarangController::class, 'store'])->name('setlan.barang');
    Route::get(BRGMASTER, [BarangController::class, 'masterBarang'])->name('setlan.barang.master');
    Route::get('/barang/masuk', [BarangController::class, 'barangMasuk'])->name('setlan.barang.masuk');

    /**# Kode Barang */
    Route::get('/barang/kodeBarang', [KodeBarangController::class, 'get'])->name('setlan.kodebarang');
    Route::get('/barang/pengaturan/kodeBarang', [KodeBarangController::class, 'index'])->name('setlan.pengaturan.kodebarang');
    Route::post('/barang/pengaturan/kdbStore', [KodeBarangController::class, 'store'])->name('setlan.pengaturan.kodeBarang.create');
    Route::patch('/barang/pengaturan/kdbEdit', [KodeBarangController::class, 'update'])->name('setlan.pengaturan.edit');
    Route::delete('/barang/pengaturan/kdbDelete/{id}', [KodeBarangController::class, 'destroy'])->name('setlan.pengaturan.hapus');


    Route::get('/barang/keluar', [BarangController::class, 'barangKeluar'])->name('setlan.barang.keluar');
    Route::patch(BRGMASTER . '/edit', [BarangController::class, 'masterBarangEdit'])->name('setlan.barang.master.edit');
    Route::post(BRGMASTER . '/add', [BarangController::class, 'masterBarangAdd'])->name('setlan.barang.master.add');
    Route::delete(BRGMASTER . '/delete/{id}', [BarangController::class, 'barangMasterDelete'])->name('setlan.barang.master.delete');

    /**# Mutasi */
    Route::get('/mutasi', [MutasiController::class, 'index'])->name('setlan.mutasi');

     /**# Akuntansi */
    Route::get('/akuntansi', [AkuntansiController::class, 'index'])->name('setlan.akuntansi');
    Route::get('/akuntansi/akun', [AkuntansiController::class, 'Akuntansi'])->name('setlan.akuntansi.akun');
    Route::get('/akuntansi/kebijakan', [AkuntansiController::class, 'kebijakan'])->name('setlan.akuntansi.kebijakan');
    Route::get('/akuntansi/akunData', [AkuntansiController::class, 'GetAkunData'])->name('setlan.akuntansi.akundata');
    Route::get('/akuntansi/akunAktif', [AkuntansiController::class, 'GetAkunAktif'])->name('setlan.akuntansi.akunaktif');
    Route::post('/akuntansi/akunCreate', [AkuntansiController::class, 'CreateAkunAktif'])->middleware('role:super_admin|admin_kab')->name('setlan.akuntansi.akuncreate');
    Route::delete('/akuntansi/akunDelete/{id}' , [AkuntansiController::class, 'DeleteAkunAktif'])->middleware('role:super_admin|admin_kab')->name('setlan.akuntansi.akundelete');

    /**# Stock */
    Route::get('/stock/stock', [StokController::class, 'index'])->name('barang.stock');
    Route::get('/stock/exrusak', [StokController::class, 'expiredRusak'])->name('barang.expired');

    /**# Satuan */
    Route::get('/satuan', [SatuanController::class, 'index'])->name('setlan.satuan');

    /**# Sub Kegiatan */
    Route::get('/msubkeg', [SubkegiatanController::class, 'show'])->name('setlan.msubkeg');

    /**# Tahun */
    Route::get('/tahun', [TahunController::class, 'index'])->name('setlan.tahun');
    Route::get('/pengaturan/tahun', [TahunController::class, 'view'])->name('setlan.pengaturan.tahun');
    Route::post('/pengaturan/tahun/create', [TahunController::class, 'store'])->name('setlan.pengaturan.tahun.create');
    Route::patch('/pengaturan/tahun/edit/{id}', [TahunController::class, 'update'])->name('setlan.pengaturan.tahun.edit');
    Route::delete('/pengaturan/tahun/delete/{id}', [TahunController::class, 'destroy'])->name('setlan.pengaturan.tahun.delete');

    /**# Kegiatan */
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('setlan.kegiatan');
    Route::get('/pengaturan/kegiatan', [KegiatanController::class, 'view'])->name('setlan.kegiatan.pengaturan');
    Route::post('/pengaturan/kegiatan/create', [KegiatanController::class, 'store'])->name('setlan.kegiatan.pengaturan.create');
    Route::patch('/pengaturan/kegiatan/edit/{id}', [KegiatanController::class, 'update'])->name('setlan.kegiatan.pengaturan.edit');
    Route::delete('/pengaturan/kegiatan/delete/{id}', [KegiatanController::class, 'destroy'])->name('setlan.kegiatan.pengaturan.delete');

    /**# Sub Kegiatan */
    Route::get('/subkegiatan', [SubkegiatanController::class, 'index'])->name('setlan.subKegiatan');
    Route::post('/pengaturan/subkegiatan/create', [SubkegiatanController::class, 'store'])->name('setlan.subKegiatan.pengaturan.create');
    Route::patch('/pengaturan/subkegiatan/edit', [SubkegiatanController::class, 'update'])->name('setlan.subKegiatan.pengaturan.edit');
    Route::delete('/pengaturan/subkegiatan/{id}/delete', [SubkegiatanController::class, 'destroy'])->name('setlan.subKegiatan.pengaturan.delete');

    /**# Unit Sub Kegiatan */
    Route::get('/unitsubkeg', [SubKegiatanAktifController::class, 'index'])->name('setlan.unitSubKeg');
    Route::patch('/unitsubkeg/edit', [SubKegiatanAktifController::class, 'update'])->name('setlan.unitSubKeg.update');
    Route::post('/unitsubkegSimpan', [SubKegiatanAktifController::class, 'saveData'])->name('setlan.unitSubKegSimpan');
    Route::get('/unitsubkegopd/{id_kabupaten}', [SubKegiatanAktifController::class, 'getOpds'])->name('setlan.unitSubKegOpd');
    Route::get('/unitsubkegunit/{id_opd}', [SubKegiatanAktifController::class, 'getUnits'])->name('setlan.unitSubKegUnit');
    Route::delete('/unitsubkegs/{id}/hapus', [SubKegiatanAktifController::class, 'destroy'])->name('setlan.unitSubKeg.delete');
    Route::get('/unitsubkegiatanGet/{idOrName?}', [SubKegiatanAktifController::class, 'getUnitSubkegiatan'])->name('setlan.getUnitSubKegiatan');
    /** Sub Kegiatan Aktif */
    Route::get('/subKegiatanAktif', [SubKegiatanAktifController::class, 'getSubKegiatanAktif'])->name('setlan.subKegiatanAktif');
    /**# Get Data Support */
    Route::get('/gsV1/opd', [GetDataController::class, 'getopd'])->name('setlan.gsV1.opd');
    Route::get('/gsV1/kab', [GetDataController::class, 'getkabupaten'])->name('setlan.gsV1.kabupaten');
    Route::get('/gsV1/{id}/kegiatan', [GetDataController::class, 'getkegiatan'])->name('setlan.gsV1.kegiatan');
});

Route::prefix('setlan')->middleware(['auth', 'cors'])->group(function () {
    Route::get('/mutasi/masukDraft', [MutasiController::class, 'mutasiMasukDraft'])->name('setlan.barang.masukdraft');
    Route::delete('/mutasi/masukDraftDelete'.IDBARANG, [MutasiController::class, 'mutasiMasukDraftDelete'])->name('setlan.barang.masukdraftdelete');
    Route::patch('/mutasi/masukdraftdate'.IDBARANG, [MutasiController::class, 'mutasiMasukDraftDate'])->name('setlan.barang.masukdraftdate');
    Route::patch('/mutasi/masukdraftupdate'.IDBARANG, [MutasiController::class, 'mutasiMasukDraftUpdate'])->name('setlan.barang.masukdraftupdate');
    Route::post('/mutasi/masukDraftStore', [MutasiController::class, 'mutasiMasukDraftStore'])->name('setlan.barang.masukdraftstore');
    Route::get('/mutasi/masukBarang', [MutasiController::class, 'mutasiBarangMasuk'])->name('setlan.barang.masukbarang');
    Route::get('/mutasi/getData', [MutasiController::class, 'show'])->name('setlan.mutasi.getData');
    Route::get('/mutasi/saldoAwal', [MutasiController::class, 'mutasiSaldoAwal'])->name('setlan.barang.saldoAwal');

    Route::post('mutasi/draft', [MutasiController::class, 'store'])->name('setlan.mutasi.draft');
    Route::post('mutasi/masuk', [MutasiController::class, 'masuk'])->name('setlan.mutasi.masuk');
    Route::post('mutasi/keluar', [MutasiController::class, 'keluar'])->name('setlan.mutasi.keluar');
});

require_once __DIR__ . '/auth.php';
