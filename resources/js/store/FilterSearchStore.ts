import { defineStore } from 'pinia';
import { useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import { Barang as InterfaceBarang } from "@/Pages/Setlan/Helper";


export const filterSearchStore = defineStore('filterSearchStore', () => {

    const filterHarga = useForm({
        harga: [0, 100000000]
    });

    const selectedCategories = ref<{}>([]);
    const search = ref<string>();
    const sort = ref<string>();
    const kd_barang_search = ref<string>();
    const sortOrder = ref<string>();
    const perPage = ref<string>('10');
    const masterBarangData = ref<{ data: InterfaceBarang[] }>({ data: [] });

    function hargaFilter() {
        filterHarga.transform((data: any) => ({
            ...data,
            harga: {
                from: filterHarga.harga[0],
                to: filterHarga.harga[1]
            },
            categories: selectedCategories.value,
            kd_barang_search: kd_barang_search.value,
            search: search.value,
            sort_by: sort.value,
            sort_order: sortOrder.value,
            per_page: perPage.value,

        })).get(route('setlan.barang.master'), {
            preserveState: true,
            replace: true,
            preserveScroll: true
        });
    }

    onMounted(() => {
        const perpage = localStorage.getItem("perPage");
        if (perpage) {
            perPage.value = JSON.parse(perpage);
        } else {
            perPage.value = '10';
            localStorage.setItem("perPage", perPage.value);
        }
      });

    watch([selectedCategories, search, sort, sortOrder, perPage, kd_barang_search], () => {
        hargaFilter();
    });

    const getDataFilter = computed(() => {
        return masterBarangData.value.data
    })

    function setPerPage(value: string) {
        perPage.value = value
    }

    return {
        filterHarga,
        selectedCategories,
        hargaFilter,
        masterBarangData,
        getDataFilter,
        search,
        sort,
        sortOrder,
        perPage,
        setPerPage,
        kd_barang_search
    }
});



