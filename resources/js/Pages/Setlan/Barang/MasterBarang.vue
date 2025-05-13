<script setup lang="ts">
import Barang from "./Index.vue";
import { filterSearchStore } from "@/store/FilterSearchStore";
import CategoryFilter from "./CategoryFilter.vue";
import { onMounted } from "vue";
import { Barang as InterfaceBarang } from "@/Pages/Setlan/Helper";
import FormDialogBarang from "@/Pages/Setlan/Components/FormDialogBarang.vue";

const filter_searchStore = filterSearchStore();

const { barangs, categories } = defineProps<{
    barangs: { data: InterfaceBarang[]; meta: any };
    categories: any[];
    breadcrumb: any;
}>();

onMounted(() => {
    filter_searchStore.masterBarangData = barangs;
});
</script>

<template>
    <Barang>
        <div class="px-4">
            <CategoryFilter :categories="categories" :barangs="barangs.data" :meta="barangs" :breadcrumb="breadcrumb" />
            <FormDialogBarang :categories="categories" />
        </div>
    </Barang>
</template>
