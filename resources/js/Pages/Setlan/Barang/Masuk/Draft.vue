<script setup lang="ts">
import {
    currencyFormat,
    useDebouncedRef,
    Mutasi,
    SubKegiatanDetail,
    safeParseFloat,
    totalSums,
    transformObject,
} from "@/Pages/Setlan/Helper";
import { reactive, watch, computed, ref } from "vue";
import { ElInputNumber } from "element-plus";
import Swal from "sweetalert2";
import { Head, router } from "@inertiajs/vue3";
import { Page } from "@/types/index";
import FormInputNumber from "@/Pages/Setlan/Components/FormInputNumber.vue";
import { Button } from "@/components/ui/button";
import Refrence from "@/Pages/Setlan/Icons/Refrence.vue";
import Breadcrumb from "@/Pages/Setlan/Components/Breadcrumb.vue";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

const props = defineProps<{
    data: { data: Mutasi[] };
    calc: any;
    breadcrumb: any;
    subkeg: { data: SubKegiatanDetail[] };
}>();

const id_barangJsonJumlah = ref<string>("");
const dbBounce = useDebouncedRef(id_barangJsonJumlah.value, 700);

watch(id_barangJsonJumlah, (newValue) => {
    dbBounce.value = newValue;
});

console.log("props", props.data.data);

function updateIdBarang(newId: string) {
    id_barangJsonJumlah.value = newId;
}

const akunList = reactive(props.data.data ?? []);
const dataUpdate = ref<any[]>([]);
const akunValues = reactive<any>({});
const dateValues = reactive<any>({});
const penyesuaianValues = reactive<any>({});
const pajakValues = reactive<any>({});
const subkegValues = reactive<Record<string, { id: string | undefined; name: string }>>(
    {}
);
const searchKeywords = ref<Record<string, string>>({});
const filteredSubkegData = computed(() => {
    return Object.fromEntries(
        Object.entries(searchKeywords.value).map(([akunId, keyword]) => {
            if (!keyword) {
                return [akunId, props.subkeg.data];
            }
            const lowerCaseKeyword = keyword.toLowerCase();

            const localResults = props.subkeg.data.filter(
                (option: any) =>
                    option.nama?.toLowerCase().includes(lowerCaseKeyword) ||
                    option.nama_keg?.toLowerCase().includes(lowerCaseKeyword)
            );

            const dbBounce = useDebouncedRef(keyword.toLowerCase(), 500);
            if (localResults.length === 0 && dbBounce.value) {
                router.get(
                    route("setlan.barang.masukdraft"),
                    { search_sub: keyword },
                    {
                        preserveState: true,
                        preserveScroll: true,
                        only: ["subkeg"],
                    }
                );
            }

            return [akunId, localResults];
        })
    );
});

akunList.forEach((akun: Mutasi) => {
    akunValues[akun.id] = akun.jumlah;
    dateValues[akun.id] = akun.tgl_beli;
    penyesuaianValues[akun.id] = akun.penyesuaian;
    pajakValues[akun.id] = akun.pajak;
});

watch(dbBounce, (newVal) => {
    const data = JSON.parse(newVal);
    router.patch(route("setlan.barang.masukdraftupdate", data), {});
});

// onMounted(() => {
akunList.forEach((akun) => {
    if (akun.id_subkeg) {
        subkegValues[akun.id] = {
            id:
                props.subkeg.data.find((s: any) => s.id === akun.id_subkeg)?.id.toString() ?? "",
            name:
                props.subkeg.data.find((s: any) => s.id === akun.id_subkeg)?.nama?.toString()! ??
                "Kosong keg",
        };
    }
});
// });

type val = number | string | null | undefined;
const handleChangeJumlah = (value: val, id: number, id_barang: string) => {
    if (value && Number(value) > 0) {
        updateIdBarang(
            JSON.stringify({
                type: "jumlah",
                id: id,
                id_barang: id_barang,
                jumlah: value,
            })
        );
    } else {
        Swal.fire({
            title: "Hapus Draft?",
            text: "Anda yakin menghapus draft ini?",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "batal",
            confirmButtonText: "Ya, Hapus",
        }).then((result) => {
            if (result.isConfirmed) {
                try {
                    router.delete(
                        route("setlan.barang.masukdraftdelete", { id, id_barang: id_barang }),
                        {
                            onSuccess: (page: Page) => {
                                Swal.fire({
                                    toast: true,
                                    icon: "success",
                                    position: "top-end",
                                    showConfirmButton: false,
                                    title: page.props.flash.success,
                                    timer: 2500,
                                });
                            },
                            preserveState: false,
                            preserveScroll: true,
                        }
                    );
                } catch (error) {
                    console.error("error", error);
                }
            }
            akunValues[id] = 1;
        });
    }
};

const totalHarga = computed(() => {
    return akunList.reduce((total, akun) => {
        const quantity = akunValues[akun.id] ?? 0;
        const pajak = pajakValues[akun.id] ?? 0;
        const penyesuaian = penyesuaianValues[akun.id] ?? 0;
        const sub =
            (safeParseFloat(akun.barang?.harga) + safeParseFloat(penyesuaian)) * quantity;
        const pajaks = pajak === 0 ? 0 : safeParseFloat(pajak) / 100;
        const subTot = sub + safeParseFloat(pajaks * sub);
        return total + subTot;
    }, 0);
});

const simpanDraft = (e: string = "masuk") => {
    const hasInvalidSubkeg = Object.values(subkegValues).some(
        (subkeg: any) => subkeg.id === "0"
    );
    if (hasInvalidSubkeg) {
        Swal.fire({
            toast: true,
            icon: "warning",
            position: "top-end",
            showConfirmButton: false,
            title: "Sub Kegiatan Belum dipilih",
            timer: 2500,
        });
        return;
    }

    if (e === "sawal") {
        Swal.fire({
            title: "Menambah Saldo Awal?",
            text: "Akan Menghapus saldo awal sebelumnya jika ada?",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "batal",
            confirmButtonText: "Ya, Buat",
        }).then((result) => {
            if (result.isConfirmed) {
                upsertBarang(dataUpdate.value, "sawal");
            } else {
                return;
            }
        });
    } else if (e === "masuk") {
        upsertBarang(dataUpdate.value, e);
    }
};

const upsertBarang = (value: any[], e: string) => {
    if (value && value?.length < 1) {
        Swal.fire({
            toast: true,
            icon: "warning",
            position: "top-end",
            showConfirmButton: false,
            title: "Tidak ada data yang di simpan",
            timer: 2500,
        });
    } else {
        router.post(
            route("setlan.barang.masukdraftstore"),
            {
                data: value,
                type: e,
            },
            {
                onSuccess: (page: Page) => {
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        position: "top-end",
                        showConfirmButton: false,
                        title: page.props.flash.success,
                        timer: 2500,
                    });
                },
                preserveState: false,
                preserveScroll: false,
            }
        );
    }
};

const tglBeli = ref<val>();
const updateTglBeli = (value: val, id: number, id_barang: string) => {
    if (value && props.data.data && props.data?.data?.length) {
        tglBeli.value = value;
        setTimeout(() => {
            router.patch(
                route("setlan.barang.masukdraftdate", {
                    type: "date",
                    id: id,
                    id_barang: id_barang,
                    tgl_beli: value,
                }),
                {}
            );
        }, 400);
    }
};

const vModelPajak = (value: { id: string; value: string }) => {
    const id = value.id.split("_")[0];
    const id_barang = value.id.split("_")[1];
    const val = value.value;
    if (id && id_barang && val) {
        updateIdBarang(
            JSON.stringify({
                type: "pajak",
                id: id,
                id_barang: id_barang,
                pajak: val,
            })
        );
    }
};
const vModelPenyesuaian = (value: { id: string; value: string }) => {
    const id = value.id.split("_")[0];
    const id_barang = value.id.split("_")[1];
    const val = value.value;
    if (id && id_barang && val) {
        updateIdBarang(
            JSON.stringify({
                type: "penyesuaian",
                id: id,
                id_barang: id_barang,
                penyesuaian: val,
            })
        );
    }
};

const updateSubKegiatan = (index: number, value: string) => {
    const id = value.split("_")[0];
    const nama_sub = value.split("_")[1];
    const mutasi_id = Number(value.split("_")[2]);
    if (id && nama_sub && mutasi_id) {
        updateIdBarang(
            JSON.stringify({
                type: "id_subkeg",
                id: mutasi_id,
                id_barang: mutasi_id,
                subkeg: id,
            })
        );
        subkegValues[mutasi_id] = { id: id, name: nama_sub };
    }
};

// Function to disable search input
const searchInput = ref<HTMLInputElement>();
const isSearchFocused = ref(false);

// Handle keyboard navigation
const handleKeyDown = (e: KeyboardEvent) => {
    if (isSearchFocused.value) {
        // Biarkan input search menangani event keyboard
        e.stopPropagation();
        return;
    }

    // Handle keyboard navigation default untuk Select
    const allowedKeys = ["ArrowUp", "ArrowDown", "Enter", "Escape"];
    if (!allowedKeys.includes(e.key) && e.key.length === 1) {
        // Focus ke input search saat user mulai mengetik
        e.preventDefault();
        searchInput.value?.focus();
    }
};

// Handle focus state
const handleSearchFocus = () => {
    isSearchFocused.value = true;
};

const handleSearchBlur = () => {
    isSearchFocused.value = false;
};
</script>

<template>

    <Head>
        <title>Draft Barang Masuk</title>
    </Head>
    <section class="text-gray-600 body-font relative mb-8">
        <div class="ml-3 py-2 sticky top-14" v-if="breadcrumb">
            <Breadcrumb :items="breadcrumb" />
        </div>
        <div>
            <div class="mb-2 w-full">
                <p class="font-bold text-xl bg-slate-100 p-1 rounded-sm shadow-lg text-center py-2 mx-4">
                    Draft Barang Masuk
                </p>
            </div>
            <div class="container px-5 pt-1 pb-4 mx-auto flex flex-wrap lg:flex-nowrap gap-x-4 gap-y-6 justify-center">
                <div
                    class="lg:w-3/4 bg-gray-200 rounded-sm overflow-hidden p-1 flex items-top justify-center relative border border-solid border-gray-400 shadow-lg">
                    <div class="w-full p-1">
                        <div class="w-full min-w-full overflow-x-auto table-container max-h-[400px]"
                            :class="{ 'min-h-[360px]': props.data.data?.length }">
                            <table v-if="props.data?.data?.length" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100 sticky-header boder border-solid border-b-2 border-gray-400">
                                    <tr>
                                        <th scope="col"
                                            class="px-1 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider whitespace-nowrap">
                                            Kd OPD | Nama OPD
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider whitespace-nowrap">
                                            Kd Unit | Nama Unit
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider max-w-30">
                                            Nama Barang | Kategori
                                        </th>

                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Kd Rek | Nama Rek
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-center text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-center text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Jumlah
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-center text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Satuan
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Harga
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-center text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Penyesuaian
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-center text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Pajak
                                        </th>

                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            <span class="ml-4">Sub Keg <span class="text-red-500">*</span>
                                            </span>
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                                            Sub Tot Harga
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 max-h-60 overflow-y-scroll"
                                    v-for="(akun, index) in akunList" :key="akun.id" text-sm text-gray-900>
                                    <tr>
                                        <td class="px-1 py-2 whitespace-nowrap">
                                            <div>{{ index + 1 }}.</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ akun.id_opd }}</div>
                                            <div class="text-sm text-gray-900">{{ akun.opd?.nama_opd }}</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ akun.id_unit }}</div>
                                            <div class="text-sm text-gray-900">{{ akun.unit?.nama_unit }}</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ akun?.barang?.nama_barang }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ akun?.barang?.kode_barang.nama }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-4 min-w-80">
                                            <div class="text-sm text-gray-500">
                                                {{ akun?.barang?.akun?.id_akun }}
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                {{ akun?.barang?.akun?.nama }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <div class="block max-w-40">
                                                <el-date-picker v-model="dateValues[akun.id]" type="date"
                                                    placeholder="Tgl Beli" format="DD-MM-YYYY" value-format="YYYY-MM-DD"
                                                    style="width: 155px"
                                                    @change="updateTglBeli($event, akun.id, akun.id_barang)"
                                                    class="no-action" />
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 max-w-40 text-sm text-gray-500">
                                            <el-input-number v-model="akunValues[akun.id]" :step="1"
                                                @change="handleChangeJumlah($event, akun.id, akun.id_barang)"
                                                class="no-action" />
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">
                                                {{ akun?.barang?.satuan.nama }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-2 text-sm text-gray-900 max-w-50 whitespace-nowrap">
                                            {{ currencyFormat(Number(akun?.barang?.harga)) }}
                                        </td>
                                        <td class="px-2 py-2 text-sm text-gray-900 max-w-50 whitespace-nowrap">
                                            <FormInputNumber @inputValueChange="vModelPenyesuaian"
                                                v-model="penyesuaianValues[akun.id]" :msg="'Rp'"
                                                :id="akun.id + '_' + akun.id_barang" />
                                        </td>
                                        <td class="px-2 py-2 text-sm text-gray-900 max-w-50 whitespace-nowrap">
                                            <FormInputNumber @inputValueChange="vModelPajak"
                                                v-model="pajakValues[akun.id]" :msg="'%'"
                                                :id="akun.id + '_' + akun.id_barang" />
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap min-w-10">
                                            <div class="min-w-96">
                                                <Select :modelValue="subkegValues[akun.id]?.id"
                                                    @update:modelValue="(val: any) => updateSubKegiatan(index, val)">
                                                    <SelectTrigger class="w-[280px]">
                                                        <SelectValue placeholder="Pilih Sub Kegiatan">
                                                            {{ subkegValues[akun.id]?.name || "Pilih Sub Kegiatan" }}
                                                        </SelectValue>
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <input type="text" placeholder="Cari Sub Kegiatan..."
                                                            class="px-2 py-1 border rounded w-full mb-2"
                                                            v-model="searchKeywords[akun.id]" @focus="handleSearchFocus"
                                                            @blur="handleSearchBlur" @keydown.stop="handleKeyDown" />
                                                        <SelectItem v-for="option in filteredSubkegData[akun.id]"
                                                            :key="option.id"
                                                            :value="option.id + '_' + option.nama + '_' + akun.id">
                                                            <div>
                                                                <div class="font-semibold text-xs">
                                                                    {{ option.nama_keg }}
                                                                </div>
                                                                <div>
                                                                    {{ option.nama }}
                                                                </div>
                                                            </div>
                                                        </SelectItem>
                                                        <div v-if="
                                                            filteredSubkegData[akun.id]?.length === 0 &&
                                                            searchKeywords[akun.id]
                                                        " class="px-2 py-1 text-gray-500">
                                                            Tidak ada hasil ditemukan.
                                                        </div>
                                                    </SelectContent>
                                                </Select>
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 text-green-800">
                                                {{
                                                    currencyFormat(
                                                        (safeParseFloat(akun.barang?.harga) +
                                                            safeParseFloat(penyesuaianValues[akun.id] ?? 0)) *
                                                        akunValues[akun.id] +
                                                        ((safeParseFloat(akun.barang?.harga) +
                                                            safeParseFloat(penyesuaianValues[akun.id] ?? 0)) *
                                                            akunValues[akun.id] *
                                                            (pajakValues[akun.id] === 0
                                                                ? 0
                                                : safeParseFloat(pajakValues[akun.id]))) /
                                                100
                                                )
                                                }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else class="bg-blue-200 text-center py-4 flex h-44">
                                <div class="text-center m-auto items-center align-middle">
                                    Draft Barang Masuk Tidak ditemukan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/4 bg-white/10 flex flex-col flex-shrink-0">
                    <h2 class="text-gray-900 text-lg mb-0 font-medium title-font align-top">
                        Total Draft Barang Masuk :
                    </h2>
                    <div class="relative mb-4 align-top">
                        <label for="name" class="leading-7 text-lg font-bold text-green-800">{{
                            currencyFormat(totalHarga)
                            }}</label>
                        <div>
                            <div>
                                <ul v-for="(entry, index) in transformObject(props.calc.akun)" :id="'akun' + index"
                                    class="flex flex-row">
                                    <div class="font-semibold">*</div>
                                    <span class="font-thin text-sm">
                                        <!-- {{ entry.value["0"].barang.akun.nama }}  -->
                                        : {{ entry.value }}
                                        <span class="font-semibold">
                                            {{ currencyFormat(totalSums(entry.value, index) ?? 0) }}
                                        </span>
                                    </span>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button @click="simpanDraft('masuk')"
                        class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                        Simpan
                    </button>
                    <el-tooltip placement="right" class="flex bg-blue-50">
                        <template #content>Simpan Sebagai Saldo Awal</template>
                        <Button class="mt-2" variant="outline" size="icon" @click="simpanDraft('sawal')">
                            <Refrence />
                        </Button>
                    </el-tooltip>

                    <p class="text-xs text-gray-500 mt-1">
                        Dengan menekan simpan daftar barang akan di pindahkan ke daftar barang masuk
                        dan menambah stok.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.table-container {
    max-height: 340px;
    overflow-y: auto;
}

.sticky-header th {
    position: sticky;
    top: 0;
    background: #f9fafb;
    z-index: 2;
}

.table-container th,
.table-container td {
    padding: 8px;
    text-align: left;
}

.demo-date-picker {
    display: flex;
    width: 50%;
    padding: 0;
    flex-wrap: wrap;
}

.demo-date-picker .block {
    padding: 30px 0;
    text-align: center;
    border-right: solid 1px var(--el-border-color);
    flex: 1;
}

.demo-date-picker .block:last-child {
    border-right: none;
}

.demo-date-picker .demonstration {
    display: block;
    color: var(--el-text-color-secondary);
    font-size: 14px;
    margin-bottom: 20px;
}

/* Customize the selected date's background color */
.el-date-table__cell.is-today .el-date-table__cell--selected {
    background-color: #11266d;
    color: white;
}

/* Customize the highlighted today's background color
 */
.el-date-table__cell.is-today:not(.el-date-table__cell--selected) {
    background-color: #e0f7fa;
    color: #122c29;
}

.no-action .el-input__inner {
    /* background-color: #ffffff;  White background */
    border-color: #0328cc;
    /* Red border */
    color: #4d01a5;
    /* Dark red text */
}

.el-date-editor .el-input__icon {
    color: #031bf7;
}

.el-date-editor .el-input__inner {
    border-color: #004ad3;
    border-width: 4px;
    border-radius: 5px;
    padding: 5px;
}

.el-date-editor .el-input__inner:hover {
    border-color: #2e2e57;
}

/* And a focus effect */
.el-date-editor .el-input__inner:focus {
    border-color: #413b92;
    /* Example focus color */
    box-shadow: 0 0 5px rgba(72, 78, 161, 0.5);
    /* Optional shadow for focus */
}
</style>
