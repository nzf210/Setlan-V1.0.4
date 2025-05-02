<script setup lang="ts">
import { basicStore } from "@/store/basicStrore";
import { loadingStore } from "@/store/LoadingStore";
import { comboSearch } from "@/store/ComboSearchStore";
import { Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Pages/Setlan/Components/MenuDropdown.vue";
import ToggleIcon from "@/Components/Icons/ToggleIcon.vue";
import { computed } from "vue";
import Spiner from "@/Pages/Setlan/Components/Spiner.vue";

const basicStoreInfo = basicStore();
const loading_store = loadingStore();
const combo_search = comboSearch();
const toggleSidebar = () => {
    basicStoreInfo.showSideBar = !basicStoreInfo.showSideBar;
};

let loadingTimeout: ReturnType<typeof setTimeout> | null = null;
let currentNavigationId: number | null = null;

router.on("start", () => {
    currentNavigationId = Date.now();
    const navigationId = currentNavigationId;
    loadingTimeout = setTimeout(() => {
        if (currentNavigationId === navigationId) {
            loading_store.setLoading(true);
        }
    }, 300);
});

router.on("progress", () => {
    console.info("progress");
});

router.on("finish", () => {
    if (loadingTimeout) {
        clearTimeout(loadingTimeout);
        loadingTimeout = null;
    }
    loading_store.setLoading(false);
    currentNavigationId = null;
});

defineProps<{
    opd?: string;
    unit?: string;
    kab?: string;
    user?: string;
}>();
</script>

<template>
    <div>
        <Spiner :nav="basicStoreInfo.showSideBar" v-if="loading_store.loading" />
    </div>

    <div class="bg-gray-800 py-2 px-4 text-light-grey flex justify-between w-full opacity-90 z-50 rounded-l-sm">
        <ToggleIcon class="my-1 w-4 h-4 sm:w-5 sm:h-5 text-gray-100 bg-dark-blue cursor-pointer md:hidden"
            @click="toggleSidebar" />
        <div class="cursor-pointer flex flex-row z-50">
            <!-- <Link :href="route('setlan.dashboard')"> -->
            <!--
                <img src="/images/supiori.png" class="h-10" alt="logo" />
                -->
            <!-- </Link> -->
            <div class="ml-4 text-white font-thin text-xs my-auto hidden sm:block">
                <p class="hidden">Nama Kab : {{ kab ?? "" }}</p>
                <p>O P D &nbsp;: {{ opd ?? "" }}</p>
                <p>Unit &nbsp; &nbsp; : {{ unit ?? "" }}</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="flex items-center gap-4">
                <h1 class="flex flex-row cursor-pointer font-renner_medium text-white" title="Representative">
                    <p class="hidden sm:block">Halo : {{ user ?? "" }}</p>
                    {{ " " }}
                </h1>
            </div>
            <div>
                <div class="mr-6 z-50">
                    <Dropdown />
                </div>
            </div>
        </div>
    </div>
</template>
