<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import SecondaryButtonVue from "@/Components/SecondaryButton.vue";
import Barang from "./Barang.vue";
import Search from "@/Pages/Setlan/Barang/Search/Index.vue";
import Pagination from "@/Pages/Setlan/Barang/Tabel/Pagination.vue";
import PerPage from "@/Pages/Setlan/Barang/PerPage.vue";
import ButtonAddbarang from "@/Pages/Setlan/Barang/ButtonAddBarang.vue";
import Plus from "@/Pages/Setlan/Icons/Plus.vue";
import ExcelGreen from "@/Pages/Setlan/Icons/ExcelGreen.vue";
import ExcelImport from "@/Pages/Setlan/Icons/ExcelImport.vue";
import Breadcrumb from "@/Pages/Setlan/Components/Breadcrumb.vue";
import { initFlowbite } from "flowbite";

onMounted(() => {
    initFlowbite();
});

import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";
import {
    ChevronDownIcon,
    FunnelIcon,
    MinusIcon,
    PlusIcon,
} from "@heroicons/vue/20/solid";
import { filterSearchStore } from "@/store/FilterSearchStore";
import { masterBarangStore } from "@/store/MasterBarangStore";
import {
    Barang as InterfaceBarang, useDebouncedRef,
    Capitalized
} from "@/Pages/Setlan/Helper";
import { router } from "@inertiajs/vue3";
import { SidebarTrigger } from "@/components/ui/sidebar";
import { Separator } from "@/components/ui/separator";
import SearchIcon from "../Icons/SearchIcon.vue";
import { XIcon } from "lucide-vue-next";

const filter_searchStore = filterSearchStore();
const master_barangStore = masterBarangStore();
const sortOptions = ref([
    { name: "Nama Barang a-z", sort: "nama_barang", current: false, order: "asc" },
    { name: "Nama Barang z-a", sort: "nama_barang", current: false, order: "desc" },
    { name: "Paling Baru", sort: "created_at", current: false, order: "desc" },
    { name: "Paling lama", sort: "created_at", current: false, order: "asc" },
    { name: "Harga: Min ke Max", sort: "harga", current: false, order: "asc" },
    { name: "Harga: Max ke Min", sort: "harga", current: false, order: "desc" },
]);

const setCurrentOption = (index: number) => {
    sortOptions.value.forEach((option, i) => {
        option.current = i === index;
        if (option.current) {
            filter_searchStore.sortOrder = option.order;
            filter_searchStore.sort = option.sort;
        }
    });
};

const props = defineProps<{
    categories: any;
    barangs: InterfaceBarang[];
    meta: any;
    breadcrumb: any;
}>();

console.log(props);

const filters = [
    {
        id: "kode_barang",
        name: "Kode Nama Barang",
        options: [{ value: "2l", label: "2L", checked: false }],
    },
];

const button = () => {
    master_barangStore.addBarangDialog();
    setTimeout(() => {
        master_barangStore.setMobileFiltersOpen(false);
    }, 400);
};

const exportXcel = () => {
    console.info("export excel");
};

const isChecked = ref(false);
watch(isChecked, (newValue) => {
    if (newValue) {
        setTimeout(() => {
            isChecked.value = false;
            router.visit(route("setlan.barang.master"));
        }, 250);
    }
});

const searchKdBarang = ref<string>("");
const dbBounce = useDebouncedRef(searchKdBarang.value, 500);
watch(searchKdBarang, (newVal) => {
    dbBounce.value = newVal;
});

watch(dbBounce, (newVal) => {
    filter_searchStore.kd_barang_search = newVal;
});
</script>

<template>
    <div class="w-full">
        <div class="w-full mx-auto">
            <!-- Mobile filter dialog -->
            <TransitionRoot as="template" :show="master_barangStore.mobileFiltersOpen">
                <Dialog class="relative lg:hidden" @close="master_barangStore.setMobileFiltersOpen(false)">
                    <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                        enter-from="opacity-0" enter-to="opacity-100"
                        leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-red-500 bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 flex">
                        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                            enter-from="translate-x-full" enter-to="translate-x-0"
                            leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                            leave-to="translate-x-full">
                            <DialogPanel
                                class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                                <div class="flex items-center justify-between px-4">
                                    <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                    <button type="button"
                                        class="mx-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                        @click="master_barangStore.setMobileFiltersOpen(false)">
                                        <span class="sr-only">Close menu</span>
                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                    </button>
                                </div>
                                <!-- price mobile -->

                                <!-- price filter -->
                                <h3 class="font-semibold ml-4 mt-8">Harga</h3>
                                <div class="flex items-center justify-between space-x-3 mt-0 flex-col">
                                    <div class="flex flex-row w-full gap-4">
                                        <div class="basis-1/3 flex flex-col ml-4">
                                            <label for="filters-harga-dari"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white ml-2">
                                                Dari
                                            </label>
                                            <input type="number" id="filters-harga-dari" placeholder="Harga min"
                                                v-model="filter_searchStore.filterHarga.harga[0]"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                        </div>
                                        <div class="basis-1/3">
                                            <label for="filters-harga-ke"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                Ke
                                            </label>

                                            <input type="number" id="filters-harga-ke"
                                                v-model="filter_searchStore.filterHarga.harga[1]"
                                                placeholder="Harga max"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                        </div>
                                    </div>
                                    <SecondaryButtonVue class="self-start mt-4 ml-4"
                                        @click="filter_searchStore.hargaFilter()">
                                        Ok
                                    </SecondaryButtonVue>
                                </div>
                                <!-- price mobile -->

                                <!-- Filters mobile -->
                                <form class="mt-4 border-t border-gray-200">
                                    <Disclosure as="div" v-for="section in filters" :key="section.id"
                                        class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                        <h3 class="-mx-2 -my-3 flow-root">
                                            <DisclosureButton
                                                class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                                <span class="font-medium text-gray-900">{{ section.name }}</span>
                                                <span class="ml-6 flex items-center">
                                                    <PlusIcon v-if="!open" class="h-5 w-5 bg-clip-text bg-blue-500"
                                                        aria-hidden="true" />
                                                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </DisclosureButton>
                                        </h3>

                                        <!-- category Mobile -->
                                        <DisclosurePanel class="pt-6">
                                            <div class="space-y-6">
                                                <div v-for="option in categories?.data" :key="option.nama"
                                                    class="flex items-center">
                                                    <input :id="`filter-mobile-${option.id_kode_barang}`"
                                                        :name="option.id_kode_barang" :value="option.id_kode_barang"
                                                        type="checkbox" :checked="option.checked"
                                                        v-model="filter_searchStore.selectedCategories"
                                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                    <label :for="`filter-mobile-${option.id_kode_barang}`"
                                                        class="ml-3 min-w-0 flex-1 text-gray-500">
                                                        {{ option.nama_kode_barang }}</label>
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                        <div class="mt-5 flex">
                                            <el-tooltip placement="top" class="flex gap-1 bg-blue-50">
                                                <template #content>Tambahkan Barang</template>
                                                <ButtonAddbarang @button="button" class="mx-auto">
                                                    <Plus /> Barang
                                                </ButtonAddbarang>
                                            </el-tooltip>
                                        </div>
                                        <div class="mt-5 flex">
                                            <el-tooltip placement="top" class="flex gap-1 bg-blue-50">
                                                <template #content>Import List Barang</template>
                                                <ButtonAddbarang @button="button" class="mx-auto" :bg="true">
                                                    <ExcelImport />
                                                </ButtonAddbarang>
                                            </el-tooltip>
                                        </div>
                                        <div class="mt-5 flex">
                                            <el-tooltip placement="top" class="flex gap-1 bg-blue-50">
                                                <template #content>Export List Barang</template>
                                                <ButtonAddbarang @button="button" class="mx-auto" :bg="true">
                                                    <ExcelGreen />
                                                </ButtonAddbarang>
                                            </el-tooltip>
                                        </div>
                                        <!-- category Mobile -->
                                    </Disclosure>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </TransitionRoot>
            <!-- Mobile filter dialog -->
            <main class="mx-auto max-w-full mt-1 rounded-sm shadow-2xl">
                <div class="flex items-baseline justify-between border-b border-gray-400 py-2 sticky top-12 z-20">
                    <div class="w-full grid grid-cols-1 gap-y-2 z-20 bg-slate-50">
                        <div class="ml-3 flex flex-row">
                            <Breadcrumb :items="breadcrumb" />
                        </div>
                        <div class="flex flex-row gap-x-1 px-2">
                            <h1 class="flex-1 w-16 text-xl whitespace-nowrap font-bold tracking-tight text-gray-900">
                                List Barang
                            </h1>
                            <div class="flex-1 max-w-44 sm:max-w-50 md:max-w-60 lg:max-w-72">
                                <Search />
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex-1 w-6 rounded-lg shadow-2xl">
                                <PerPage />
                            </div>
                            <!-- Menu Sort -->
                            <div class="flex-1 max-w-16 shadow-2xl bg-slate-200 rounded-lg items-center align-middle">
                                <Menu as="div" class="relative inline-block text-left w-full mx-auto my-auto">
                                    <div class="mx-auto my-auto bg-slate-200 w-full p-1 rounded-md shadow-lg">
                                        <MenuButton
                                            class="ml-1 group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900 hover:font-semibold">
                                            Sort
                                            <ChevronDownIcon
                                                class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                                aria-hidden="true" />
                                        </MenuButton>
                                    </div>

                                    <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems
                                            class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-4 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1">
                                                <MenuItem v-for="(option, index) in sortOptions" :key="option.name"
                                                    v-slot="{ active }">
                                                <button type="button" :class="[
                                                    sortOptions[index].current
                                                        ? 'font-semibold text-gray-900'
                                                        : 'text-blue-900',
                                                    'block px-4 py-2 text-sm',
                                                ]" @click.prevent="setCurrentOption(index)">
                                                    {{ option.name }}
                                                </button>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                        <!-- Menu Sort end -->
                                    </transition>
                                </Menu>
                            </div>
                            <div class="flex-none w-8">
                                <!-- btn filter mobile -->
                                <button type="button" class="text-gray-400 hover:text-gray-500 lg:hidden"
                                    @click="master_barangStore.setMobileFiltersOpen(true)">
                                    <span class="sr-only">Filters</span>
                                    <FunnelIcon class="h-5 w-5" aria-hidden="true" />
                                </button>
                                <!-- btn filter mobile end-->
                            </div>
                        </div>
                    </div>
                </div>

                <section aria-labelledby="products-heading" class="pb-24 pt-6">
                    <!-- toggle reset -->
                    <div class="ml-2 sticky top-9">
                        <label class="inline-flex items-center cursor-pointer">
                            <input v-model="isChecked" type="checkbox" value="" class="sr-only peer" />
                            <div
                                class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                            </div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Reset filter</span>
                        </label>
                    </div>
                    <!-- toggle reset end -->
                    <h2 id="products-heading" class="sr-only">List Barang</h2>
                    <div class="grid grid-cols-1 gap-x-2 gap-y-2 lg:grid-cols-4 xl:grid-cols-5 md:grid-cols-3">
                        <!-- Filters -->
                        <form class="hidden lg:block grid-cols-1 lg:ml-2">
                            <!-- price filter -->
                            <div class="flex items-center justify-between space-x-3">
                                <div>
                                    <h3 class="text-lg font-semibold">Filter by Harga</h3>
                                    <div class="basis-1/3">
                                        <label for="filters-harga-dari"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Dari
                                        </label>
                                        <div class="flex flex-row">
                                            <p class="mt-2 mr-2 text-slate-600 font-xs">Rp</p>
                                            <input type="number" id="filters-harga-dari" placeholder="Harga min"
                                                v-model="filter_searchStore.filterHarga.harga[0]"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                        </div>
                                    </div>
                                    <div class="basis-1/3 mt-2">
                                        <label for="filters-harga-ke"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Ke
                                        </label>
                                        <div class="flex flex-row">
                                            <p class="mt-2 mr-2 text-slate-600 font-xs">Rp</p>
                                            <input type="number" id="filters-harga-ke"
                                                v-model="filter_searchStore.filterHarga.harga[1]"
                                                placeholder="Harga max"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                        </div>
                                    </div>
                                </div>
                                <SecondaryButtonVue class="self-end" @click="filter_searchStore.hargaFilter()">
                                    Ok
                                </SecondaryButtonVue>
                            </div>
                            <Disclosure as="div" v-for="section in filters" :key="section.id"
                                class="border-b border-gray-300 py-6" v-slot="{ open }">
                                <h3 class="-my-3 flow-root bg-red-800">
                                    <DisclosureButton
                                        class="flex w-full items-center justify-between bg-slate-50 py-3 text-sm text-gray-400 hover:text-gray-500">
                                        <span class="font-medium text-gray-900">{{ section.name }}</span>
                                        <span class="ml-6 flex items-center">
                                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </h3>
                                <DisclosurePanel class="pt-6">
                                    <!-- search -->
                                    <div class="relative">
                                        <input
                                            class="appearance-none border-2 pl-10 border-gray-300 hover:border-gray-400 transition-colors rounded-md w-full py-2 px-3 text-gray-800 leading-tight focus:outline-none focus:ring-purple-600 focus:border-purple-600 focus:shadow-outline"
                                            id="searchkdbarang" type="text" placeholder="Cari Kd barang..."
                                            v-model="searchKdBarang" />
                                        <div class="absolute right-0 inset-y-0 flex items-center"
                                            @click="() => (searchKdBarang = '')">
                                            <XIcon />
                                        </div>
                                        <div class="absolute left-0 inset-y-0 flex items-center">
                                            <SearchIcon class="ml-2" />
                                        </div>
                                    </div>
                                    <!-- search -->
                                    <div class="space-y-4 max-h-60 overflow-y-auto">
                                        <div v-for="category in categories.data" :key="category.id_kode_barang"
                                            class="flex items-center ml-1 py-1">
                                            <input :id="`filter-${category.id_kode_barang}`"
                                                :value="category.id_kode_barang" type="checkbox"
                                                v-model="filter_searchStore.selectedCategories"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                            <label :for="`filter-${category.id_kode_barang}`"
                                                class="ml-3 text-sm text-gray-600 bg-slate-100 px-2 py-1 rounded-sm">
                                                {{ Capitalized(category.nama_kode_barang) }}</label>
                                        </div>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                            <div class="mt-5 flex">
                                <el-tooltip placement="top" class="flex gap-1 bg-blue-50">
                                    <template #content>Tambahkan Barang</template>
                                    <ButtonAddbarang @button="button" class="mx-auto">
                                        <Plus /> Barang
                                    </ButtonAddbarang>
                                </el-tooltip>
                            </div>
                            <div class="mt-5 flex">
                                <el-tooltip placement="top" class="flex gap-1 p-5">
                                    <template #content>Import List Barang</template>
                                    <ButtonAddbarang @button="exportXcel" class="mx-auto" :bg="true">
                                        <ExcelImport class="h-12 w-12" />
                                    </ButtonAddbarang>
                                </el-tooltip>
                            </div>
                            <div class="mt-5 flex">
                                <el-tooltip placement="top" class="flex gap-1">
                                    <template #content>Export List Barang</template>
                                    <ButtonAddbarang @button="exportXcel" class="mx-auto" :bg="true">
                                        <ExcelGreen />
                                    </ButtonAddbarang>
                                </el-tooltip>
                            </div>
                        </form>

                        <!-- Product grid -->
                        <div class="md:col-span-3 xl:col-span-4" v-if="barangs && barangs.length">
                            <Barang :barangs="barangs" :meta="meta" />
                            <div class="mt-12">
                                <Pagination :meta="meta" />
                            </div>
                        </div>
                        <div v-else class="m-auto w-full col-span-3 text-center font-semibold">
                            Tidak di temukan barang
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</template>

<style scoped>
#searchkdbarang::placeholder {
    font-size: 12px;
    color: #999;
}
</style>
