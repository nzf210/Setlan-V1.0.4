<script setup lang="ts">
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import { filterSearchStore } from "@/store/FilterSearchStore";
import SearchIcon from "@/Pages/Setlan/Icons/SearchIcon.vue";
onMounted(() => {
  initFlowbite();
});

const filter_searchStore = filterSearchStore();
let debounceTimeout: any = null;
const search = (e: any) => {
  if (debounceTimeout) {
    clearTimeout(debounceTimeout);
  }
  debounceTimeout = setTimeout(() => {
    filter_searchStore.search = e.target.value;
  }, 1500);
};
</script>

<template>
  <form class="p-0">
    <div class="flex flex-row">
      <label for="search-dropdown" class="sr-only">Cari</label>
      <div class="relative w-full">
        <input
          type="search"
          id="search-dropdown"
          class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
          placeholder="Cari..."
          required
          @input="search"
        />
        <button
          type="button"
          class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        >
          <SearchIcon />
          <span class="sr-only">Cari ... </span>
        </button>
      </div>
    </div>
  </form>
</template>
