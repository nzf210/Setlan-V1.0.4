<script setup lang="ts">
import { ElMessageBox, ElDialog } from "element-plus";
import { masterBarangStore } from "@/store/MasterBarangStore";
import ComboSelect from "@/Components/headlessui/ComboBoxSelect.vue";
import { computed, onUnmounted, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { Checkbox } from "@/components/ui/checkbox";

const master_barangStore = masterBarangStore();
const handleClose = (done: () => void) => {
    ElMessageBox.confirm("Yakin meninggalkan form ini?")
        .then(() => {
            done();
        })
        .catch((e: any) => {
            console.error(e);
        });
};

const timeoutIdAkun = ref<number | null>(null);
const timeoutIdSatuan = ref<number | null>(null);
const timeoutIdCategory = ref<number | null>(null);

const indexAktif = ref(0);
const indexSatuan = ref(0);
const indexCategory = ref(0);

const akunAktif = ref<any>();
const satuanAktif = ref<any>();
const categoryAktif = ref<any>();

const loadSatuanAktif = (qry: string, setOptions: Function) => {
    if (timeoutIdSatuan.value) {
        clearTimeout(timeoutIdSatuan.value);
    }

    timeoutIdSatuan.value = setTimeout(() => {
        router.visit(route("setlan.satuan"), {
            method: "get",
            data: { nama: qry },
            replace: true,
            preserveState: true,
            async: true,
            onSuccess: (page) => {
                const satuanaktif = page.props.flash?.value?.data as any[];
                const isReady = satuanaktif.some(
                    (akun: { id_satuan: number }) => akun.id_satuan === master_barangStore.id_satuan
                );

                if (master_barangStore.editMode && master_barangStore.id_satuan && !isReady) {
                    satuanaktif.unshift({
                        id: master_barangStore.id_satuan,
                        nama: master_barangStore.nama_satuan,
                    });
                }

                if (master_barangStore.editMode && master_barangStore.id_satuan) {
                    indexSatuan.value = satuanaktif.findIndex(
                        (akun: { id_satuan: number }) => akun.id_satuan === master_barangStore.id_satuan
                    );

                    satuanAktif.value = {
                        id: satuanaktif[indexSatuan.value]?.id_satuan,
                        name: satuanaktif[indexSatuan.value]?.nama_satuan,
                    };
                }

                const options = satuanaktif.map((akun: { id_satuan: number; nama_satuan: string }) => ({
                    id: akun.id_satuan,
                    name: akun.nama_satuan,
                }));

                setOptions(options);
            },
            onError: (errors) => {
                console.error("Error loading satuan aktif:", errors);
            },
        });
    }, 500);
};

let loadAkunAktif = (qry: string, setOptions: Function) => {
    if (timeoutIdAkun.value) {
        clearTimeout(timeoutIdAkun.value);
    }

    timeoutIdAkun.value = setTimeout(() => {
        router.visit(route("setlan.akuntansi.akunaktif"), {
            data: {
                nama: qry,
            },
            method: "get",
            replace: true,
            preserveState: true,
            async: true,
            onSuccess: (page) => {
                const akunaktif = page.props.flash?.value as any[];

                const isReady = akunaktif.some(
                    (akun: { id_akun_aktif: number }) => akun.id_akun_aktif == master_barangStore.id_akun_aktif
                );

                if (master_barangStore.editMode && master_barangStore.id_akun_aktif && !isReady) {
                    akunaktif.unshift({
                        id: master_barangStore.id_akun_aktif,
                        nama: master_barangStore.nama_akun,
                    });
                }

                if (master_barangStore.editMode && master_barangStore.id_akun_aktif) {
                    indexAktif.value = akunaktif.findIndex(
                        (akun: { id_akun_aktif: number }) => akun.id_akun_aktif === master_barangStore.id_akun_aktif
                    );

                    akunAktif.value = {
                        id: akunaktif[indexAktif.value]?.id_akun_aktif,
                        name: akunaktif[indexAktif.value]?.nama_akun,
                    };
                }

                const options = akunaktif.map((akun: { id_akun_aktif: number; nama_akun: string }) => ({
                    id: akun.id_akun_aktif,
                    name: akun.nama_akun,
                }));

                setOptions(options);
            },
            onError: (errors) => {
                console.error("Error loading akun aktif:", errors);
            },
        });
    }, 300);
};

onUnmounted(() => {
    if (timeoutIdSatuan.value) {
        clearTimeout(timeoutIdSatuan.value);
    }
    if (timeoutIdAkun.value) {
        clearTimeout(timeoutIdAkun.value);
    }
    if (timeoutIdCategory.value) {
        clearTimeout(timeoutIdCategory.value);
    }
});

const loadCategoryAktif = (qry: string, setOptions: Function) => {
    if (timeoutIdCategory.value) {
        clearTimeout(timeoutIdCategory.value);
    }

    timeoutIdCategory.value = setTimeout(() => {
        router.visit(route("setlan.kodebarang"), {
            data: { nama: qry },
            method: "get",
            preserveState: true,
            replace: true,
            async: true,
            onSuccess: (page) => {
                const categoryaktif = page.props.flash?.value?.data as any[];
                const isReady = categoryaktif.some(
                    (akun: { id_kode_barang: number }) => akun.id_kode_barang == master_barangStore.id_kode_barang
                );

                if (master_barangStore.editMode && master_barangStore.id_kode_barang && !isReady) {
                    categoryaktif.unshift({
                        id: master_barangStore.id_kode_barang,
                        nama: master_barangStore.nama_kode_barang,
                    });
                }

                if (master_barangStore.editMode && master_barangStore.id_kode_barang) {
                    indexCategory.value = categoryaktif.findIndex(
                        (akun: { id_kode_barang: number }) => akun.id_kode_barang == master_barangStore.id_kode_barang
                    );

                    categoryAktif.value = {
                        id: categoryaktif[indexCategory.value]?.id_kode_barang,
                        name: categoryaktif[indexCategory.value]?.nama_kode_barang,
                    };
                }

                const options = categoryaktif.map((akun: { id_kode_barang: number; nama_kode_barang: string }) => ({
                    id: akun.id_kode_barang,
                    name: akun.nama_kode_barang,
                }));

                setOptions(options);
            },
            onError: (errors) => {
                console.error("Error loading category aktif:", errors);
            },
        });
    }, 100);
};

watch(akunAktif, (nv) => {
    master_barangStore.setIdAkun(nv?.id);
    master_barangStore.setNamaAkun(nv?.name);
});

watch(categoryAktif, (nv) => {
    master_barangStore.setKdBarangId(nv?.id);
    master_barangStore.setKdBarangNama(nv?.name);
});

watch(satuanAktif, (nv) => {
    master_barangStore.setSatuan(nv?.id);
    master_barangStore.setSatuanNama(nv?.name);
});
const chk = ref(false);
const checkbox = () => {
    chk.value = !chk.value;
};

watch(chk, (nv) => {
    master_barangStore.setIsAddDraft(nv);
});

/// Setup Format Rupiah

interface FormatRupiahOptions {
    decimal?: boolean;
}
// Format currency with TypeScript type
const formatRupiah = (value: number | string, options: FormatRupiahOptions = { decimal: true }): string => {
    const numericValue = typeof value === 'string' ? parseFloat(value) : value;

    if (isNaN(numericValue)) return '';

    const [integerPart, decimalPart] = numericValue.toFixed(2).split('.');
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return options.decimal
        ? `${formattedInteger},${decimalPart}`
        : formattedInteger;
};

// Parse currency input to number
const parseRupiah = (formattedValue: string): number => {
    const sanitizedValue = formattedValue
        .replace(/[^\d,]/g, '') // Remove non-digits and non-commas
        .replace(/,(\d{2})$/, '.$1') // Convert decimal comma to dot
        .replace(/\./g, ''); // Remove thousand separators

    return parseFloat(sanitizedValue) || 0;
};

// Computed property untuk two-way binding
const hargaFormatted = computed<string>({
    get: () => {
        return master_barangStore.harga
            ? formatRupiah(master_barangStore.harga)
            : '';
    },
    set: (value: string) => {
        const parsedValue = parseRupiah(value);
        master_barangStore.harga = parsedValue;
    }
});

// Validation untuk input keyboard
const handleNumberInput = (event: KeyboardEvent) => {
    const allowedKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ',', '.'];
    const isNumberKey = allowedKeys.includes(event.key);

    if (!isNumberKey) {
        event.preventDefault();
        return;
    }

    // Handle decimal separator
    const currentValue = (event.target as HTMLInputElement).value;
    if (['.', ','].includes(event.key) && currentValue.includes(',')) {
        event.preventDefault();
    }
};

</script>

<template>
    <el-dialog v-model="master_barangStore.dialogVisible"
        :title="master_barangStore.isAddProduct ? 'Tambah Barang' : 'Ubah Barang'" width="500"
        :before-close="handleClose">
        <div>
            <form class="max-w-md mx-auto" @submit.prevent="
                master_barangStore.editMode && master_barangStore.isAddProduct
                    ? master_barangStore.addBarang()
                    : master_barangStore.editMode
                        ? master_barangStore.editBarang()
                        : master_barangStore.addBarang()
                ">
                <div v-if="Object.keys($page.props.errors).length > 0" class="mb-4 pb-4">
                    <div v-for="(errorMessages, field) in $page.props.errors" :key="field" class="text-sm text-red-500">
                        * {{ errorMessages }}
                    </div>
                </div>
                <div class="relative w-full mb-5 group mt-3">
                    <input v-model="master_barangStore.nama_barang" type="text" name="floating_nama_barang"
                        id="floating_nama_barang"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="floating_nama_barang"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8 after:content-['*'] after:ml-0.5 after:text-red-500">
                        Nama Barang
                    </label>
                </div>
                <div class="relative w-full mb-5 group">
                    <input v-model="master_barangStore.merek" type="text" name="floating_merek" id="floating_merek"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="floating_merek"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8 after:content-['*'] after:ml-0.5 after:text-red-500">Merek
                        Barang
                    </label>
                </div>

                <div class="relative w-full mb-5 group">
                    <input v-model="master_barangStore.type" type="text" name="type_barang" id="floating_type_barang"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " />
                    <label for="floating_type_barang"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8 after:ml-0.5 after:text-red-500">
                        Tipe Barang
                    </label>
                </div>
                <div class="relative w-full mb-5 group">
                    <input v-model="hargaFormatted" type="text" name="harga_barang" id="floating_harga_barang"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required @keypress="handleNumberInput" />
                    <label for="floating_harga_barang"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8 after:content-['*'] after:ml-0.5 after:text-red-500">
                        Harga
                    </label>
                </div>
                <div class="relative w-full mb-5 group">
                    <label for="rek"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white after:content-['*'] after:ml-0.5 after:text-red-500">
                        Pilih Rekening
                    </label>
                    <ComboSelect :load-options="loadAkunAktif" :index="indexAktif" v-model="akunAktif"
                        :id="'rek' + indexAktif" />
                </div>
                <div class="relative w-full mb-5 group">
                    <label for="satuan"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white after:content-['*'] after:ml-0.5 after:text-red-500">
                        Satuan
                    </label>
                    <ComboSelect :load-options="loadSatuanAktif" :index="indexSatuan" v-model="satuanAktif"
                        :id="'satuan' + indexSatuan" />
                </div>
                <div class="relative w-full mb-5 group">
                    <label for="kd_barang"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white after:content-['*'] after:ml-0.5 after:text-red-500">
                        Kode Barang
                    </label>
                    <ComboSelect :load-options="loadCategoryAktif" :index="indexCategory" v-model="categoryAktif"
                        :id="'kode_barang ' + indexCategory" />
                    <div class="flex items-center space-x-2 mt-2" v-if="!master_barangStore.editMode">
                        <Checkbox id="terms" :checked="!chk" @update:checked="checkbox"
                            class="w-4 h-4 text-blue-600 bg-blue-50 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-400 focus:ring-2 dark:bg-gray-300 dark:border-gray-200" />
                        <label for="terms"
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Tambah barang di draft masuk setelah disimpan
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Submit
                </button>
            </form>
        </div>
    </el-dialog>
</template>

<style scoped>
.swal2-container {
    z-index: 2060 !important;
}
</style>
