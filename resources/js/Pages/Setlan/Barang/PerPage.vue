<script lang="ts" setup>
import { ref, watch } from "vue";
import { filterSearchStore } from "@/store/FilterSearchStore";

const filter_searchStore = filterSearchStore();
const valueInput = ref<string>(filter_searchStore.perPage);

watch(
    () => valueInput.value,
    (newValue: any) => {
        filter_searchStore.perPage = newValue;
        localStorage.setItem("perPage", newValue);
    }
);
</script>

<template>
    <div class="flex items-center justify-start">
        <div
            class="flex items-center space-x-2 filament-tables-pagination-records-per-page-selector rtl:space-x-reverse">
            <label class="inline-flex items-center px-2">
                <span class="text-sm font-medium dark:text-white"> Tampil </span>
                <select v-model="valueInput" @change="filter_searchStore.setPerPage(valueInput)"
                    class="h-8 text-sm ml-1 pr-8 leading-none transition duration-75 border-gray-300 rounded-lg shadow-sm outline-none focus:border-yellow-500 focus:ring-1 focus:ring-inset focus:ring-yellow-500 dark:text-white dark:bg-gray-700 dark:border-gray-600 dark:focus:border-yellow-500">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="-1">All</option>
                </select>
            </label>
        </div>
    </div>
</template>
