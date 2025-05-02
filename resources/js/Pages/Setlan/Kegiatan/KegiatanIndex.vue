<script setup lang="ts">
import { ref, watch, onUnmounted } from "vue";
import { router, Head, useForm } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Table,
    TableHeader,
    TableRow,
    TableHead,
    TableBody,
    TableCell,
} from "@/components/ui/table";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from "@/components/ui/pagination";
import {
    Plus,
    Edit,
    Trash2,
    ListTree,
    AlertCircle,
    CheckCircle,
    X,
    UploadIcon,
} from "lucide-vue-next";
import { ro } from "element-plus/es/locales.mjs";

interface PaginationFilters {
    search?: string;
    year?: string;
    per_page?: number;
}

interface Kegiatan {
    id: number;
    id_keg: string;
    nama: string;
    tahun: number;
}

interface PaginatedResult<Kegiatan> {
    data: Kegiatan[];
    total: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    next_page_url: string | null;
    path: string;
    per_page: number;
    current_page: number;
    prev_page_url: string | null;
    to: number;
    links: { url: string | null; label: string; active: boolean }[];
}

const props = defineProps<{
    kegiatans: PaginatedResult<Kegiatan>;
    filters: PaginationFilters;
    years: Array<{ year: number }>;
    auth: { role: string };
}>();

const filters = ref({
    search: props.filters.search || "",
    year: props.filters.year || "",
    per_page: props.filters.per_page || 10,
});

const page = ref(props.kegiatans.current_page);

const showModal = ref(false);
const form = ref({
    id: 0,
    id_keg: "",
    nama: "",
    tahun: "",
});
const editing = ref(false);

const roles = props?.auth.role === "admin_kab" || props?.auth.role === "super_admin";

const columns = roles
    ? [
        { key: "id_keg", header: "ID Kegiatan" },
        { key: "nama", header: "Nama Kegiatan" },
        { key: "tahun", header: "Tahun" },
        { key: "actions", header: "Aksi" },
    ]
    : [
        { key: "id_keg", header: "ID Kegiatan" },
        { key: "nama", header: "Nama Kegiatan" },
        { key: "tahun", header: "Tahun" },
    ];

const openSubKeg = (kegiatan: Kegiatan) => {
    router.visit(`/subkegiatan/${kegiatan.id}`);
};

const submit = () => {
    if (editing.value) {
        router.patch(
            route("setlan.kegiatan.pengaturan.edit", { id: form.value.id }),
            form.value,
            {
                onSuccess: (page) => {
                    showModal.value = false;
                    form.value = { id: 0, id_keg: "", nama: "", tahun: "" };
                    if (page.props.flash.success) {
                        showNotifikasi("success", "Berhasil mengubah kegiatan");
                    } else {
                        showNotifikasi("error", "Gagal mengubah kegiatan");
                    }
                },
                onError() {
                    showNotifikasi("error", "Gagal mengubah kegiatan");
                },
            }
        );
    } else {
        router.post(route("setlan.kegiatan.pengaturan.create"), form.value, {
            onSuccess: () => {
                showModal.value = false;
                form.value = { id: 0, id_keg: "", nama: "", tahun: "" };
                showNotifikasi("success", "Berhasil menambah kegiatan");
            },

            onError() {
                showNotifikasi("error", "Gagal menambahkan kegiatan");
            },
        });
    }
};

const editKegiatan = (kegiatan: Kegiatan) => {
    form.value = { ...kegiatan, tahun: kegiatan.tahun.toString(), id: kegiatan.id };
    editing.value = true;
    showModal.value = true;
};

const deleteKegiatan = (id: number) => {
    if (confirm("Apakah Anda yakin ingin menghapus?")) {
        router.delete(route("setlan.kegiatan.pengaturan.delete", { id: id }), {
            onSuccess() {
                showNotifikasi("success", "Berhasil menghapus kegiatan");
            },
            onError() {
                showNotifikasi("error", "Gagal menghapus kegiatan");
            },
        });
    }
};

let timeoutId: ReturnType<typeof setTimeout> | null = null;
watch(
    () => filters.value.search,
    (newSearch) => {
        // Clear timeout sebelumnya
        if (timeoutId) clearTimeout(timeoutId);

        // Set timeout baru
        timeoutId = setTimeout(() => {
            updateFilters();
        }, 700);
    }
);

watch([() => filters.value.year, () => filters.value.per_page], () => {
    updateFilters();
});

watch(showModal, () => {
    if (!showModal.value) {
        form.value = { id: 0, id_keg: "", nama: "", tahun: "" };
        editing.value = false;
    }
});

const updateFilters = () => {
    router.get(
        route("setlan.kegiatan"),
        {
            search: filters.value.search,
            year: filters.value.year,
            per_page: filters.value.per_page,
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        }
    );
};

// Cleanup timeout saat komponen di-unmount
onUnmounted(() => {
    if (timeoutId) clearTimeout(timeoutId);
});

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

const file = ref<File | null>(null);
const showImportModal = ref(false);

const triggerFileInput = () => {
    const fileInput = document.getElementById("file-upload-element");
    fileInput?.click();
};

const handleFileSelect = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files?.length) {
        file.value = input.files[0];
    }
};

const formImport = useForm({
    id: "",
    id_kd_barang: "",
    id_kab: "",
    tahun: new Date().getFullYear(),
    nama: "",
});
</script>

<template>

    <Head>
        <title>Master Kegiatan</title>
    </Head>
    <div class="p-6">
        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-bold">Master Kegiatan</h1>
            <div class="flex space-x-2 mb-6" v-if="roles">
                <Button @click="showImportModal = true"> Import Excel </Button>
                <Button @click="showModal = true">
                    <Plus class="w-4 h-4 mr-2" /> Tambah Kegiatan
                </Button>
            </div>
        </div>

        <div class="relative my-2">
            <div class="relative max-w-sm">
                <Input v-model="filters.search" placeholder="Cari kegiatan..." class="pl-10 pr-8 w-max-sm" />
                <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                <button v-if="filters.search" @click="filters.search = ''"
                    class="absolute right-2 top-2.5 text-muted-foreground hover:text-foreground transition-colors">
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>
        <div v-if="!kegiatans.data.length" class="mb-6">
            <div class="p-6 text-center border rounded-lg bg-gray-50">
                <div class="mb-4 text-gray-500">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900">Data Kosong</h3>
                <p class="text-gray-500 mb-4">kegiatan tidak ditemukan</p>
                <Button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    @click="showModal = true" as="button">
                    Tambah Baru
                </Button>
            </div>
        </div>
        <div class="border rounded-lg overflow-hidden" v-else>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="col in columns" :key="col.key">{{ col.header }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="row in kegiatans.data" :key="row.id">
                        <TableCell class="font-bold">{{ row.id_keg }}</TableCell>
                        <TableCell class="font-bold">{{ row.nama }}</TableCell>
                        <TableCell>{{ row?.tahun }}</TableCell>
                        <TableCell class="flex gap-2" v-if="roles">
                            <Button variant="outline" title="Edit" size="sm" @click="editKegiatan(row)">
                                <Edit class="w-4 h-4" color="#2865c8" />
                            </Button>
                            <Button variant="outline" title="Hapus" size="sm" @click="deleteKegiatan(row.id)">
                                <Trash2 class="w-4 h-4" color="#e7724b" />
                            </Button>
                            <Button variant="outline" title="Sub Kegiatan" size="sm" @click="openSubKeg(row)">
                                <ListTree class="w-4 h-4" />
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <!-- Pagination -->
            <div class="flex justify-center mt-4">
                <Pagination v-slot="{ page: currentPage }" :items-per-page="kegiatans.per_page" :total="kegiatans.total"
                    :sibling-count="1" :default-page="kegiatans.current_page" show-edges
                    @update:page="(newPage) => router.visit(kegiatans.path + '?page=' + newPage)">
                    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
                        <PaginationFirst :disabled="kegiatans.current_page === 1" />
                        <PaginationPrev :disabled="!kegiatans.prev_page_url" />

                        <template v-for="(item, index) in items">
                            <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value">
                                <Button class="w-10 h-10 p-0"
                                    :variant="kegiatans.current_page === item.value ? 'default' : 'outline'" :disabled="!kegiatans.links.find((l) => parseInt(l.label) === item.value)?.url
                                        ">
                                    {{ item.value }}
                                </Button>
                            </PaginationListItem>
                            <PaginationEllipsis v-else :key="index + '-'" />
                        </template>

                        <PaginationNext :disabled="!kegiatans.next_page_url" />
                        <PaginationLast :disabled="kegiatans.current_page === kegiatans.last_page" />
                    </PaginationList>
                </Pagination>
            </div>
            <!-- Pagination -->
        </div>

        <Dialog v-model:open="showModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ editing ? "Edit" : "Tambah" }} Kegiatan</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <Label for="id_keg">ID Kegiatan</Label>
                        <Input v-model="form.id_keg" required />
                    </div>
                    <div>
                        <Label for="nama">Nama Kegiatan</Label>
                        <Input v-model="form.nama" required />
                    </div>
                    <div>
                        <Label for="tahun">Tahun</Label>
                        <Select v-model="form.tahun">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih Tahun" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="year in years" :key="year.year" :value="year.year">
                                    {{ year.year }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <Button type="submit" class="w-full">Simpan</Button>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Import Modal -->
        <Dialog v-model:open="showImportModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Import dari Excel</DialogTitle>
                </DialogHeader>

                <form @submit.prevent="formImport.post(route('setlan.pengaturan.import'))">
                    <div class="grid gap-4 py-4">
                        <div class="grid w-full max-w-sm items-center gap-1.5">
                            <Label for="file-upload-element">Pilih File Excel</Label>
                            <Input id="file-upload-element" type="file" @change="handleFileSelect" accept=".xlsx,.xls"
                                class="hidden" />
                            <div class="flex items-center gap-2">
                                <Button type="button" variant="outline" @click="triggerFileInput">
                                    <UploadIcon class="mr-2 h-4 w-4 mx-auto" />
                                    {{ file ? file.name : "Unggah File" }}
                                </Button>
                                <Label for="file-upload-element" v-if="!file" class="text-sm text-muted-foreground">
                                    Belum ada file dipilih
                                </Label>
                            </div>
                        </div>
                        <Button type="submit">Proses Import</Button>
                    </div>
                </form>
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
