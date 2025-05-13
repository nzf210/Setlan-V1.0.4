<script setup lang="ts">
import { currencyFormat, PaginatedMutasiResponse } from "@/Pages/Setlan/Helper";
import { reactive, ref } from "vue";
import Breadcrumb from "@/Pages/Setlan/Components/Breadcrumb.vue";
import Pagination from "@/Pages/Setlan/Barang/Tabel/Pagination.vue";

const props = defineProps<{
    data: PaginatedMutasiResponse;
    calc: any;
    breadcrumb: any;
}>();

const meta = reactive(props.data) as any;

const calcValues = Object.values(props.calc).reduce((sum, item: any) => {
    return sum + item.total_jumlah;
}, 0);

const akunName = Object.values(props.calc).map((item: any) => {
    return { nama_akun: item.akun_name, jumlah: item.total_jumlah };
});

const akun_name = ref<any>(akunName);
const totalStock = ref<number | unknown>(calcValues);
const akunList = reactive(props.data.data);
const akunValues = reactive<any>({});

akunList.forEach((akun: any) => {
    akunValues[akun.id] = akun?.jumlah!;
});
</script>

<template>
    <section class="text-gray-600 body-font relative mb-8">
        <div class="ml-3 py-2 sticky top-14 z-10 bg-gray-100" v-if="breadcrumb">
            <Breadcrumb :items="breadcrumb" />
        </div>
        <div>
            <div class="mb-2 w-full">
                <p class="font-bold text-lg bg-slate-100 p-1 rounded-sm shadow-lg text-center mx-4">
                    Stock Barang
                </p>
            </div>

            <div class="container px-5 pt-1 pb-4 mx-auto flex flex-wrap lg:flex-nowrap gap-x-4 gap-y-6 justify-center">
                <div
                    class="lg:w-3/4 max-h-[420px] bg-gray-200 rounded-sm overflow-hidden p-1 flex items-top justify-center relative border border-solid border-gray-400 shadow-lg">
                    <div class="w-full">
                        <div class="w-full min-w-full overflow-x-auto table-container">
                            <table v-if="props?.data?.data?.length" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky-header">
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
                                            Sub Keg
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
                                            <div>{{ index + (data?.meta?.from ?? 0) }}.</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ akun?.opd?.id_opd }}</div>
                                            <div class="text-sm text-gray-900">{{ akun?.opd?.nama_opd }}</div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ akun.unit?.id_unit }}</div>
                                            <div class="text-sm text-gray-900">
                                                {{ akun?.unit?.nama_unit }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ akun?.barang?.nama_barang }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ akun?.barang?.category?.nama }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ akun?.barang?.akun?.id_akun }}
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                {{ akun?.barang?.akun?.nama }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 max-w-40 text-sm text-gray-500">
                                            <span
                                                class="px-3 inline-flex text-xs leading-5 font-semibold rounded-sm bg-slate-200 text-green-800 whitespace-nowrap">
                                                23 jan 2024
                                            </span>
                                        </td>
                                        <td class="px-2 py-2 max-w-40 text-sm text-gray-500">
                                            <span
                                                class="px-3 inline-flex text-xs leading-5 font-semibold rounded-sm bg-slate-200 text-green-800">
                                                {{ akun.jumlah }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-sm bg-green-100 text-green-800">
                                                {{ akun?.barang?.satuan.nama }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-2 py-2 text-sm text-right text-gray-900 max-w-50 whitespace-nowrap">
                                            {{ currencyFormat(akun?.barang?.harga ?? 0) }}
                                        </td>
                                        <td class="pr-2 pl-6 py-2 text-sm text-right text-gray-900 max-w-50">
                                            {{ currencyFormat(akun.penyesuaian ?? 0) }}
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <span v-if="akun?.pajak"
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 text-green-800">
                                                {{ akun?.pajak + " %" }}
                                            </span>
                                            <span v-else
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 text-green-800">
                                                0%
                                            </span>
                                        </td>
                                        <td class="px-2 py-2 min-w-96">
                                            <div
                                                class="w-full px-2 inline-flex text-xs leading-5 font-semibold rounded-sm bg-green-200 text-green-800">
                                                {{ akun.subkeg?.nama }}
                                            </div>
                                        </td>

                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-sm bg-green-200 text-green-800">
                                                {{ currencyFormat(akun?.barang?.harga * akun?.jumlah) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else class="bg-blue-200 text-center py-4 flex h-32">
                                <p class="text-center m-auto">Barang tidak ditemukan</p>
                            </div>
                        </div>
                        <div v-if="props.data && props.data.meta.total > 25" class="pt-1 pb-10 text-center">
                            <Pagination :meta="meta" />
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/4 bg-white/10 flex flex-col flex-shrink-0">
                    <div>
                        <button
                            class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                            Export
                        </button>
                    </div>
                    <h2 class="text-gray-900 text-lg mb-0 font-medium title-font align-top">
                        Total Stock Barang :
                    </h2>
                    <div class="relative mb-4 align-top">
                        <label for="name" class="leading-7 text-lg font-bold text-green-800">{{
                            currencyFormat(totalStock as number)
                        }}</label>
                        <div>
                            <div>
                                <ul v-for="(entry, index) in akun_name" :id="'akun' + index" class="flex flex-row">
                                    <div class="font-semibold">*</div>
                                    <span class="font-thin text-sm">
                                        {{ entry.nama_akun }}
                                        :
                                        <span class="font-semibold">
                                            {{ currencyFormat(entry.jumlah ?? 0) }}
                                        </span>
                                    </span>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Rekap barang masuk persediaan</p>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.table-container {
    max-height: 320px;
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
</style>
