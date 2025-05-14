import { defineStore } from 'pinia';
import {  ref } from 'vue';
import Swal from "sweetalert2";
import { router, useForm } from '@inertiajs/vue3';
import { Barang } from "@/Pages/Setlan/Helper";
import { Page } from "@/types/index";

export const masterBarangStore = defineStore('masterBarangStore', () => {
    const id_barang = ref<string>("");
    const nama_barang = ref<string>("");

    const merek = ref<string>("");
    const type = ref<string>("");
    const harga = ref<number>(0);
    const dateValue = ref<Array<string>>();

    const id_kode_barang = ref<number>();
    const nama_kode_barang = ref<string>();

    const nama_akun = ref<string>('');
    const id_akun_aktif = ref<number>();

    const nama_satuan = ref<string>('');
    const id_satuan = ref<number>();

    const dialogVisible = ref<boolean>(false);
    const editMode = ref<boolean>(false);
    const isAddProduct = ref<boolean>(false);
    const isAddDraft = ref<boolean>(false);
    const mobileFiltersOpen = ref<boolean>(false);


    function setIsAddDraft(value: boolean) {
        isAddDraft.value = value;
    }
    function setMobileFiltersOpen(value: boolean) {
        mobileFiltersOpen.value = value;
    }

    function setKdBarangId(value: number) {
        id_kode_barang.value = value;
    }

    function setKdBarangNama(value: string) {
        nama_kode_barang.value = value;
    }
    function setIdAkun(value: number) {
        id_akun_aktif.value = value;
    }

    function setNamaAkun(value: string) {
        nama_akun.value = value;
    }

    function setSatuan(value: number) {
        id_satuan.value = value;
    }

    function setSatuanNama(value: string) {
        nama_satuan.value = value;
    }

    function addBarangDialog() {
        isAddProduct.value = true;
        dialogVisible.value = true;
        editMode.value = false;
    }

    function editBarangDialog(e: Barang, b:boolean) {

        id_barang.value = e.id_barang;
        nama_barang.value = e.nama_barang;
        merek.value = e.merek!;
        type.value = e.type!;
        harga.value = Number(e.harga);

        setKdBarangId(e.kode_barang.id_kode_barang);
        setKdBarangNama(e.kode_barang.nama_kode_barang);

        setIdAkun(e.akun.id_akun_aktif);
        setNamaAkun(e.akun.nama_akun_aktif);

        setSatuan(e.satuan?.id_satuan);
        setSatuanNama(e.satuan?.nama_satuan);

        isAddProduct.value = b;
        dialogVisible.value = true;
        editMode.value = true;
    }

    function addBarang() {
        const formData = new FormData();
        formData.append('nama_barang', nama_barang.value);
        formData.append('merek', merek.value);
        formData.append('type', type.value);
        formData.append('id_kode_barang', String(id_kode_barang.value));
        formData.append('id_satuan', String(id_satuan.value));
        formData.append('harga', String(harga.value));
        formData.append('id_akun_aktif', String(id_akun_aktif.value));
        formData.append('is_add_draft', isAddDraft.value ? '0' : '1');

        if (!nama_barang.value || !id_akun_aktif.value || !id_kode_barang.value || !id_satuan.value || (harga.value <= 0 || isNaN(harga.value))) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Pastikan isian wajib terisi dengan benar',
                timer: 1000
            });
            return;
        }

        try {
            router.post(route('setlan.barang.master.add'), formData, {
                onSuccess: (page:Page) => {
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        position: "top-end",
                        showConfirmButton: false,
                        title: page.props.flash.success,
                        timer: 2500
                    });
                    resetFormData();
                },
            })
        } catch (error) {
            console.error(error);
            Swal.fire('Masalah', 'Maaf terjadi kesalahan', 'error');
        }
    };

    function editBarang() {
        try {
            const form = useForm({
                id_barang: id_barang.value,
                nama_barang: nama_barang.value,
                merek: merek.value,
                type: type.value,
                harga: harga.value,
                id_kode_barang: id_kode_barang.value,
                id_satuan: id_satuan.value,
                id_akun: id_akun_aktif.value
            });

            form.patch(route('setlan.barang.master.edit'), {
                onSuccess: page => {
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        position: "top-end",
                        showConfirmButton: false,
                        title: page.props.flash.success,
                        timer: 2500
                    });
                    resetFormData();
                },
                preserveScroll: true,
                preserveState: true
            })

        } catch (error) {
            console.error(error);
            Swal.fire('Masalah', 'Maaf terjadi kesalahan', 'error');
        }
    };

function deleteBarang(e: string) {
        Swal.fire({
            title: 'Yakin Hapus Barang ini?',
            text: "Barang ini akan di hapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'batal',
            confirmButtonText: 'ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                try {
                    router.delete('master/delete/' + e, {
                        onSuccess: page => {
                            Swal.fire({
                                toast: true,
                                icon: "success",
                                position: "top-end",
                                showConfirmButton: false,
                                title: page.props.flash.success,
                                timer: 2500
                            });
                        },
                        preserveState: true,
                        preserveScroll: true
                    })
                } catch (error) {
                    console.error(error);
                    Swal.fire('Masalah', 'Maaf terjadi kesalahan', 'error');
                    throw error;
                }
        }
    })
};

function resetFormData() {
        id_barang.value = "";
        nama_barang.value = "";
        merek.value = "";
        type.value = "";
        harga.value = 0;
        dateValue.value = [];
        setNamaAkun("");
        setIdAkun(0);
        setKdBarangId(0);
        setKdBarangNama("");
        setSatuanNama("");
        setSatuan(0);

        dialogVisible.value = false;
        editMode.value = false;
        isAddProduct.value = false;
    };

function addToCart(e: Barang) {saveToCart(e.id_barang)};

function saveToCart(id: string) {
    const formMutasiDraft = useForm({id_barang: id});
    formMutasiDraft.post(route('setlan.mutasi.draft'), {
        onSuccess: (page: Page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success,
                    timer: 2500
                });
                return
            }
                Swal.fire({
                    toast: true,
                    icon: "info",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.info,
                    timer: 2500
                });
            },
            preserveScroll: true,
            preserveState: true
        })
}

    return {
        id_barang,
        nama_barang,
        id_kode_barang,
        nama_kode_barang,
        merek,
        type,
        harga,
        dateValue,
        dialogVisible,
        editMode,
        isAddProduct,
        addBarang,
        resetFormData,
        addBarangDialog,
        editBarangDialog,
        editBarang,
        deleteBarang,
        addToCart,
        id_akun_aktif,
        setIdAkun,
        setNamaAkun,
        nama_akun,
        nama_satuan,
        id_satuan,
        setSatuan,
        setSatuanNama,
        setIsAddDraft,
        mobileFiltersOpen,
        setMobileFiltersOpen,
        setKdBarangId,
        setKdBarangNama
    }
});
