<script setup lang="ts">
import TabLink from "@/Pages/Setlan/Components/TabLink.vue";
import { menu } from "@/Pages/Setlan/Helper";
import SecureIcon from "@/Pages/Setlan/Icons/SecureIcon.vue";
import Kebijakan from "@/Pages/Setlan/Icons/Kebijakan.vue";
</script>

<template>
    <div class="border-b border-gray-200 dark:border-gray-700 fixed">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
            <TabLink :href="route(menu.tabAkun.akun)" :active="route().current(menu.tabAkun.akun)">
                <SecureIcon class="mr-2 max-h-4 max-w-4" /> Akuntansi Barang
            </TabLink>
            <TabLink :href="route(menu.tabAkun.kebijakan)" :active="route().current(menu.tabAkun.kebijakan)">
                <Kebijakan class="mr-2 max-h-4 max-w-4" /> Kebijakan Akuntansi
            </TabLink>
        </ul>
    </div>
</template>
