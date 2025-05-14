<script setup lang="ts">
import {
    Barang as InterfaceBarang,
    currencyFormat,
    Capitalized,
} from "@/Pages/Setlan/Helper";
import { masterBarangStore } from "@/store/MasterBarangStore";
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import { Copy, Plus, Settings } from "lucide-vue-next";

onMounted(() => {
    initFlowbite();
});

defineProps<{
    barangs: InterfaceBarang[];
}>();

const master_barangStore = masterBarangStore();
const editBarang = (br: InterfaceBarang, b: boolean = false) => {
    master_barangStore.editBarangDialog(br, b);
};
</script>

<template>
    <div
        class="mt-2 grid grid-cols-2 gap-x-4 gap-y-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 lg:gap-x-1 xl:gap-x-2 container px-4">
        <div v-for="barang in barangs" :key="barang.id_barang"
            class="group relative rounded-sm shadow-lg p-2 bg-slate-200 w-40 sm:w-44 lg:w-40">
            <div
                class="aspect-h-1 aspect-w-1 w-36 sm:w-40 lg:w-36 overflow-hidden rounded-md bg-gray-200 lg:aspect-none h-48">
                <!-- <img alt="" v-if="barang.product_images.length > 0" :src="`/${barang.product_images[0].image}`"
                                :alt="barang.imageAlt ?? ''"
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full" /> -->
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                    :alt="barang.nama_barang"
                    class="h-full w-full object-cover object-center lg:h-full lg:w-full p-1" />
                <!-- add to cart icon -->
                <div
                    class="absolute h-4 inset-0 m-auto flex flex-wrap items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer w-24">
                    <div class="bg-blue-700 px-1 py-1 rounded-full ml-2" @click="master_barangStore.addToCart(barang)">
                        <el-tooltip placement="top" class="flex gap-1 bg-blue-50">
                            <template #content>Tambah Ke daftar Barang Masuk</template>
                            <button type="button" class="text-white flex">
                                <Plus class="w-4 h-4 m-auto" />
                            </button>
                        </el-tooltip>
                    </div>
                    <div class="bg-blue-700 px-1 py-1 rounded-full ml-2" @click="editBarang(barang)">
                        <el-tooltip placement="top" class="flex gap-1 bg-blue-50">
                            <template #content>Edit Barang</template>
                            <button type="button" class="text-white flex">
                                <Settings class="w-4 h-4 m-auto" />
                            </button>
                        </el-tooltip>
                    </div>
                    <div class="bg-blue-700 px-1 py-1 rounded-full ml-2" @click="editBarang(barang, true)">
                        <el-tooltip placement="bottom" class="flex bg-blue-50">
                            <template #content>Copy Barang</template>
                            <button type="button" class="text-white flex">
                                <Copy class="w-4 h-4" />
                            </button>
                        </el-tooltip>
                    </div>
                </div>
                <!-- end -->
            </div>
            <div class="mt-2 flex justify-between">
                <div>
                    <h3 class="text-sm text-gray-700">
                        <span aria-hidden="true" class="" />
                        {{ barang.nama_barang }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Merk: {{ barang.merek }}</p>
                    <p class="text-sm font-medium text-gray-900">
                        <span class="font-thin text-xs"> Harga : </span>
                        {{ currencyFormat(Number(barang.harga) ?? 0) }}
                    </p>
                </div>
            </div>
            <p>
                <span class="font-thin text-slate-600 text-xs">Nama Kode Barang :</span>
                <span class="font-xs font-thin">
                    {{ Capitalized(barang.kode_barang.nama_kode_barang) }}
                </span>
            </p>
        </div>
    </div>
</template>
