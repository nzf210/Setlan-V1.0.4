<script setup lang="ts">
import { nextTick, ref, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { AlertCircle, CheckCircle, Plus, X } from "lucide-vue-next";
import Dialog from "@/components/ui/dialog/Dialog.vue";
import DialogContent from "@/components/ui/dialog/DialogContent.vue";
import DialogHeader from "@/components/ui/dialog/DialogHeader.vue";
import DialogTitle from "@/components/ui/dialog/DialogTitle.vue";
import DialogDescription from "@/components/ui/dialog/DialogDescription.vue";

const showModalAdd = ref<boolean>(false);
const showImportModal = ref<boolean>(false);
const props = defineProps<{
    initialData: any;
    auth: { role: string };
    unitSubKegs: any;
}>();

interface Kegiatan {
    id_kegiatan: number;
    kode_kegiatan: string;
    nama_kegiatan: string;
    tahun: number;
}

interface SubKegiatan {
    id_kegiatan: number;
    id_sub_kegiatan: number;
    kode_sub_kegiatan: string;
    nama_sub_kegiatan: string;
    tahun: number;
}

const form = useForm({
    kabupaten_id: null,
    opd_id: null,
    unit_id: null,
    kode_sub_kegiatan: null,
    id_sub_kegiatan: null,
});

console.log("Initial Data:", props.initialData);

const opds = ref(props.initialData.opds || []);
const units = ref(props.initialData.units || []);
const unitSubKegiatans = ref<any>([]);

const notifikasi = ref({
    show: false,
    type: "success",
    message: "",
});

const showNotifikasi = (type: "success" | "error", message: string): void => {
    notifikasi.value = {
        show: true,
        type,
        message,
    };
    setTimeout(() => {
        notifikasi.value.show = false;
    }, 3000);
};

watch(
    () => props.unitSubKegs,
    () => (unitSubKegiatans.value = [])
);

watch(
    () => form.kabupaten_id,
    async (kabupatenId) => {
        if (!kabupatenId) return;
        router.visit(route("setlan.unitSubKegOpd", { id_kab: kabupatenId }), {
            replace: true,
            preserveScroll: true,
            preserveState: true,
            method: "get",
            async: true,
            onSuccess: (page) => {
                opds.value = page.props.flash.value;
            },
            onError: (errors) => {
                console.error(errors);
            },
        });
    }
);

watch(
    () => form.opd_id,
    async (opdId) => {
        if (!opdId) return;
        router.visit(route("setlan.unitSubKegUnit", { id_opd: opdId }), {
            method: "get",
            replace: true,
            preserveScroll: true,
            preserveState: true,
            async: true,
            onSuccess: (page) => {
                units.value = page.props.flash.value;
            },
            onError: (errors) => {
                console.error(errors);
            },
        });
    }
);

const searchQuery = ref("");
const subKegiatans = ref<any[]>([]);
const isOpen = ref(false);
import _ from "lodash";
import TableUnitSubKeg from "./TableUnitSubKeg.vue";
const fetchSubKegiatans = _.debounce(async () => {
    router.visit(
        route("setlan.subKegiatanAktif", {
            kode_sub_keg: form.kode_sub_kegiatan,
            search: searchQuery.value,
        }),
        {
            async: true,
            method: "get",
            preserveScroll: true,
            preserveState: true,
            onSuccess: (page: any) => {
                unitSubKegiatans.value = page.props.unitSubKegs;
                const response = page.props.flash.value;
                console.log("response", response);
                subKegiatans.value = response;
            },
        }
    );
}, 300);

watch(
    () => form.unit_id,
    () => fetchSubKegiatans()
);

watch(searchQuery, (newQuery) => {
    if (newQuery.length > 0 || newQuery.length === 0) {
        fetchSubKegiatans();
    }
});

const submit = () => {
    form.post(route("setlan.unitSubKegSimpan"), {
        preserveScroll: true,
        async: true,
        preserveState: true,
        onSuccess: (page: any) => {
            if (page.props.flash.error) {
                showNotifikasi("error", page.props.flash.error);
                return;
            }
            unitSubKegiatans.value = page.props.unitSubKegs;
            showNotifikasi("success", page.props.flash.success);
            showModalAdd.value = false;
            form.reset();
        },
        onError: (errors) => {
            showNotifikasi("error", errors.response);
        },
    });
};

const searchInput = ref<HTMLInputElement>();
const isSearchFocused = ref(false);

// Handle keyboard navigation
const handleKeyDown = (e: KeyboardEvent) => {
    if (isSearchFocused.value) {
        // Biarkan input search menangani event keyboard
        e.stopPropagation();
        return;
    }

    // Handle keyboard navigation default untuk Select
    const allowedKeys = ["ArrowUp", "ArrowDown", "Enter", "Escape"];
    if (!allowedKeys.includes(e.key) && e.key.length === 1) {
        // Focus ke input search saat user mulai mengetik
        e.preventDefault();
        searchInput.value?.focus();
    }
};

// Handle focus state
const handleSearchFocus = () => {
    isSearchFocused.value = true;
};

const handleSearchBlur = () => {
    isSearchFocused.value = false;
};

watch(isOpen, (open) => {
    if (open) {
        nextTick(() => {
            searchInput.value?.focus();
        });
    }
});
</script>

<template>

    <Head>
        <title>Form Pemilihan Unit</title>
        <meta name="description" content="Form Pemilihan Unit" />
        <link rel="icon" href="/favicon.ico" />
    </Head>

    <div class="flex justify-between mb-6 mt-5 mx-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Daftar Unit Sub Kegiatan</h1>
            <p class="text-muted-foreground text-sm">Daftar kegiatan terpadu per unit kerja</p>
        </div>
        <div class="flex space-x-2 mb-6" v-if="props?.auth.role !== 'operator'">
            <Button @click="showImportModal = true"> Import Excel </Button>
            <Button @click="showModalAdd = true">
                <Plus class="w-4 h-4 mr-2" /> Tambah Sub Kegiatan Unit
            </Button>
        </div>
    </div>
    <div>
        <TableUnitSubKeg :data="unitSubKegiatans && unitSubKegiatans?.data?.length > 0
            ? unitSubKegiatans
            : unitSubKegs
            " :role="props?.auth.role" />
    </div>
    <div>
        <Dialog v-model:open="showModalAdd">
            <DialogContent class="sm:max-w-[625px]">
                <DialogHeader>
                    <DialogTitle>Tambah Sub Keg Unit</DialogTitle>
                    <DialogDescription>
                        Pilih sub kegiatan untuk unit yang akan ditambahkan.
                    </DialogDescription>
                </DialogHeader>

                <div class="p-6 pt-0">
                    <h1 class="text-2xl font-bold mb-6">Form Pemilihan Unit</h1>
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Super Admin: Pilih Kabupaten -->
                        <div v-if="props?.auth.role === 'super_admin'" class="grid gap-2">
                            <label for="kabupaten">Kabupaten</label>
                            <Select v-model="form.kabupaten_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Kabupaten" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="kab in initialData.kabupatens" :key="kab.id_kab"
                                        :value="kab.id_kab">
                                        {{ kab.nama_kab }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- OPD Dropdown (Super Admin & Admin Kab) -->
                        <div class="grid gap-2">
                            <label for="opd">OPD</label>
                            <Select v-model="form.opd_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih OPD" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="opd in opds" :key="opd.id_opd" :value="opd.id_opd">
                                        {{ opd.nama_opd }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Unit Dropdown (Semua kecuali Operator) -->
                        <div v-if="props?.auth.role !== 'operator'" class="grid gap-2">
                            <label for="unit">Unit</label>
                            <Select v-model="form.unit_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Unit" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="unit in units" :key="unit.id_unit" :value="unit.id_unit">
                                        {{ unit.nama_unit }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Sub Kegiatan Dropdown -->
                        <div class="grid gap-2">
                            <label for="sub_kegiatan">Sub Kegiatan</label>
                            <Select v-model="form.id_sub_kegiatan">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Sub Kegiatan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <div class="sticky top-0 p-2 bg-background z-10 space-y-1">
                                        <input ref="searchInput" v-model="searchQuery"
                                            placeholder="Cari sub kegiatan..." @focus="handleSearchFocus"
                                            @blur="handleSearchBlur" @keydown.stop="handleKeyDown"
                                            class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                                    </div>
                                    <template v-if="subKegiatans.length > 0">
                                        <SelectItem v-for="sub in subKegiatans" :key="sub?.id_sub_kegiatan"
                                            :value="sub.id_sub_kegiatan">
                                            <div>
                                                <div class="font-semibold text-xs">
                                                    {{ sub?.kegiatan?.nama_kegiatan }}
                                                </div>
                                                <div>
                                                    {{ sub.nama_sub_kegiatan }}
                                                </div>
                                            </div>
                                        </SelectItem>
                                    </template>
                                    <div v-else class="py-4 text-center text-sm text-muted-foreground">
                                        Tidak ditemukan sub kegiatan
                                    </div>
                                </SelectContent>
                            </Select>
                        </div>

                        <Button type="submit" :disabled="form.processing"> Simpan Data </Button>
                    </form>
                </div>
            </DialogContent>
        </Dialog>
        <!-- Notifikasi -->
        <transition enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="notifikasi.show"
                class="fixed bottom-4 right-4 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto overflow-hidden"
                :class="notifikasi.type === 'success' ? 'bg-green-500' : 'bg-red-500'">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <CheckCircle v-if="notifikasi.type === 'success'" class="h-6 w-6 text-white" />
                            <AlertCircle v-else class="h-6 w-6 text-white" />
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-white">
                                {{ notifikasi.message }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="notifikasi.show = false"
                                class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Close</span>
                                <X class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
