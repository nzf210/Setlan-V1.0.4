<script setup lang="ts">
import TabLink from "@/Pages/Setlan/Components/TabLink.vue";
import { menu } from "@/Pages/Setlan/Helper";
import BuildingStorefront from "@/Pages/Setlan/Icons/BuildingStorefront.vue";
import DocumentArrowUp from "@/Pages/Setlan/Icons/DocumentArrowUp.vue";
import MenuTabDropdown from "@/Pages/Setlan/Components/MenuTabDropdown.vue";

const master = menu.tabBrg.master;
const masuk = menu.tabBrg.masuk;
const keluar = menu.tabBrg.keluar;

const data = {
    master: {
        url: route(master),
        isActive: route().current(master),
    },
    keluar: {
        url: route(keluar),
        isActive: route().current(keluar),
    },
};
</script>

<template>
    <div class="border-b border-gray-200 dark:border-gray-700 md:ml-3 z-20 top-14">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400 rounded-lg">
            <TabLink :href="data.master.url" :active="data.master.isActive">
                <BuildingStorefront class="mr-2 max-h-4 max-w-4" /> Master Barang
            </TabLink>
            <TabLink :href="'#'">
                <span class="flex flex-row w-28">
                    <MenuTabDropdown />
                </span>
            </TabLink>
            <TabLink :href="data.keluar.url" :active="data.keluar.isActive">
                <DocumentArrowUp class="mr-2 max-h-4 max-w-4" /> Mutasi Keluar
            </TabLink>
        </ul>
    </div>
</template>
