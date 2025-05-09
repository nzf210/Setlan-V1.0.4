<script setup lang="ts">
import { ref, watch, onUnmounted } from "vue";
import { router, Head, useForm } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogDescription,
} from "@/components/ui/dialog";
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
    CornerDownRight,
    UploadIcon,
    SquarePlus,
} from "lucide-vue-next";
import { PaginationFilters } from "@/types";

interface SubKegiatan {
    id_sub_kegiatan?: number;
    id_kegiatan?: number;
    kode_kegiatan: string;
    kode_sub_kegiatan: string;
    nama_kegiatan: string;
    nama_sub_kegiatan?: string;
    type: string;
    tahun?: string | number;
    parent_id?: string | number;
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
interface Tahun {
    tahun: number;
}

const props = defineProps<{
    subkegiatans: PaginatedResult<SubKegiatan>;
    // kegiatan: PaginatedResult<Kegiatan>;
    filters: PaginationFilters;
    tahun: Array<{ tahun: number }>;
    auth: { role: string };
}>();

const filters = ref({
    search: props.filters.search || "",
    tahun: props.filters.tahun || "",
    per_page: props.filters.per_page || 10,
});

const page = ref(props.subkegiatans.current_page);

console.log("subkegiatans", props);

const showModal = ref(false);
const formAdd = ref<{
    id_kegiatan?: number | string;
    kode_kegiatan: string;
    nama_kegiatan: string;
    tahun: string;
    nama_sub_kegiatan: string;
    kode_sub_kegiatan: string;
}>({
    id_kegiatan: "",
    kode_kegiatan: "",
    nama_kegiatan: "",
    tahun: "",
    nama_sub_kegiatan: "",
    kode_sub_kegiatan: "",

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

const editing = ref(false);
const roles = props?.auth.role === "admin_kab" || props?.auth.role === "super_admin";
const columns = roles
    ? [
        { key: "id_keg", header: "Kode Kegiatan / Sub Kegiatan" },
        { key: "nama", header: "Nama Kegiatan / Sub Kegiatan" },
        { key: "tahun", header: "Tahun" },
        { key: "actions", header: "Aksi" },
    ]
    : [
        { key: "id_keg", header: "Kode Kegiatan / Sub Kegiatan" },
        { key: "nama", header: "Nama Kegiatan / Sub Kegiatan" },
        { key: "tahun", header: "Tahun" },
    ];

const openSubKeg = (kegiatan: SubKegiatan) => {
    /*
              // router.visit(`/subkegiatan/${kegiatan.id}`);*/
};

const submit = () => {
    if (editing.value) {
        router.patch(route("setlan.subKegiatan.pengaturan.edit"), form.value, {
            onSuccess: (page) => {
                showModal.value = false;
                form.value = { id: "", kode: "", nama: "", tahun: "", namasub: "" };
                if (page.props.flash.success) {
                    showNotifikasi("success", "Berhasil mengubah sub kegiatan");
                } else {
                    showNotifikasi("error", "Gagal mengubah sub kegiatan");
                }
            },
            onError() {
                showNotifikasi("error", "Gagal mengubah sub kegiatan");
            },
        });
    } else {
        router.post(route("setlan.subKegiatan.pengaturan.create"), form.value, {
            onSuccess: () => {
                showModal.value = false;
                form.value = { id: 0, kode: "", nama: "", tahun: "", namasub: "" };
                showNotifikasi("success", "Berhasil menambah sub kegiatan");
            },

            onError() {
                showNotifikasi("error", "Gagal menambahkan sub kegiatan");
            },
        });
    }
};
const showAddSubDialog = ref(false);
const parentKegiatan = ref<{
    id_kegiatan: number;
    kode_kegiatan: string;
    tahun: number;
    nama_kegiatan: string;
    nama_sub_kegiatan: string;
}>({
    id_kegiatan: 0,
    tahun: 0,
    nama_kegiatan: "",
    nama_sub_kegiatan: "",
    kode_kegiatan: "",
});

const formImport = useForm({
    id: "",
    kode: "",
    nama: "",
    tahun: "",
});

const newSub = ref<SubKegiatan>({
    kode_sub_kegiatan: "",
    nama_sub_kegiatan: "",
    id_kegiatan: 0,
    tahun: 0,
    id_sub_kegiatan: 0,
    type: "",
    parent_id: "",
    nama_kegiatan: "",
    kode_kegiatan: ''
});

const editKegiatan = (kegiatan: SubKegiatan) => {
    router.visit(route("setlan.gsV1.kegiatan", { id: kegiatan.parent_id }), {
        preserveState: true,
        async: true,
        preserveScroll: true,
        onSuccess: (page) => {
            parentKegiatan.value = {
                id_kegiatan: Number(kegiatan.parent_id),
                kode_kegiatan: page.props.flash.value[0].kode_kegiatan,
                nama_kegiatan: page.props.flash.value[0].nama_kegiatan,
                nama_sub_kegiatan: kegiatan.nama_sub_kegiatan!,
                tahun: 0,
            };
            newSub.value = {
                id_kegiatan: Number(kegiatan.parent_id),
                nama_kegiatan: kegiatan.nama_kegiatan!,
                nama_sub_kegiatan: kegiatan.nama_sub_kegiatan!,
                tahun: 0,
                id_sub_kegiatan: kegiatan.id_kegiatan,
                kode_sub_kegiatan: kegiatan.kode_kegiatan,
                type: kegiatan.type,
                parent_id: kegiatan.parent_id,
                kode_kegiatan: kegiatan.kode_kegiatan,
            };
            form.value = {
                id_kegiatan: kegiatan.parent_id,
                id_sub_kegiatan: kegiatan.id_kegiatan,
                nama_kegiatan: page.props.flash.value[0].nama_kegiatan,
                kode_kegiatan: page.props.flash.value[0].kode_kegiatan,
                nama_sub_kegiatan: kegiatan.nama_sub_kegiatan,
                kode_sub_kegiatan: kegiatan.kode_kegiatan,
            };
        },
    });
    editing.value = true;
    showModal.value = true;
};

const deleteKegiatan = (id: SubKegiatan) => {
    if (confirm("Apakah Anda yakin ingin menghapus?")) {
        router.delete(route("setlan.subKegiatan.pengaturan.delete", { id: id.id_kegiatan }), {
            onSuccess() {
                showNotifikasi("success", "Berhasil menghapus sub kegiatan");
            },
            onError() {
                showNotifikasi("error", "Gagal menghapus sub kegiatan");
            },
        });
    }
};

let timeoutId: ReturnType<typeof setTimeout> | null = null;
watch(
    () => filters.value.search,
    () => {
        if (timeoutId) clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            updateFilters();
        }, 700);
    }
);

watch([() => filters.value.tahun, () => filters.value.per_page], () => {
    updateFilters();
});

watch(showModal, () => {
    if (!showModal.value) {
        form.value = { id: 0, kode: "", nama: "", tahun: "", namasub: "" };
        editing.value = false;
    }
});

const updateFilters = () => {
    router.get(
        route("setlan.subKegiatan"),
        {
            search: filters.value.search,
            year: filters.value.tahun,
            per_page: filters.value.per_page,
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        }
    );
};

onUnmounted(() => {
    if (timeoutId) clearTimeout(timeoutId);
});

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

const openAddSubDialog = (row: SubKegiatan) => {
    parentKegiatan.value = {
        id_kegiatan: Number(row.id_kegiatan),
        nama_kegiatan: row.nama_kegiatan,
        kode_kegiatan: row.kode_kegiatan,
        nama_sub_kegiatan: '',
        tahun: 0,
    };
    newSub.value = {
        id_kegiatan: Number(row.id_kegiatan),
        nama_kegiatan: row.nama_kegiatan,
        parent_id: row.parent_id,
        kode_kegiatan: row.kode_kegiatan,
        kode_sub_kegiatan: "",
        nama_sub_kegiatan: '',
        id_sub_kegiatan: 0,
        tahun: row.tahun,
        type: row.type,
    };
    showAddSubDialog.value = true;
};
const submitSubKegiatan = () => {
    router.post(route("setlan.subKegiatan.pengaturan.create"), newSub.value, {
        preserveScroll: true,
        onSuccess: (page) => {
            showAddSubDialog.value = false;
            newSub.value = {
                id_sub_kegiatan: 0,
                nama_kegiatan: "",
                id_kegiatan: 0,
                nama_sub_kegiatan: "",
                tahun: "",
                kode_sub_kegiatan: "",
                type: "",
                parent_id: "",
                kode_kegiatan: "",
            };
            if (page.props.flash.success) {
                showNotifikasi("success", "Berhasil menambah sub kegiatan");
            } else {
                showNotifikasi("error", "Gagal menambah sub kegiatan");
            }
        },
    });
};

// Modals Untuk Sub Kegiatan
interface Kegiatan {
    id_kegiatan?: string | number | undefined;
    nama_kegiatan: string;
    kode_kegiatan: string;
    tahun: number;
    type: string;
}

const searchQuery = ref("");
const selectedKegiatan = ref<Kegiatan | null>(null);
const kegiatanOptions = ref<Kegiatan[]>([]);
const isLoading = ref(false);
const showModalAdd = ref(false);
const errors = ref<Record<string, string>>({});

const form = ref<{ [key: string]: any | undefined }>({
    id_sub_kegiatan: 0,
    nama_sub_kegiatan: "",
    id_kegiatan: 0,
    kode_sub_kegiatan: "",
    // tahun: props.tahunOptions[0]?.year.toString() || "",
});

// Debounced search
let searchTimeout: number;
watch(searchQuery, (newVal) => {
    clearTimeout(searchTimeout);
    if (newVal.length > 2) {
        searchTimeout = window.setTimeout(searchKegiatan, 500);
    }
});

const searchKegiatan = () => {
    isLoading.value = true;
    try {
        router.get(
            route("setlan.subKegiatan"),
            { search: searchQuery.value },
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page: any) => {
                    kegiatanOptions.value = page.props?.kegiatan?.data;
                },
            }
        );
    } catch (error) {
        console.error("Search failed:", error);
    } finally {
        isLoading.value = false;
    }
};

const submitForm = () => {
    try {
        router.post(route("setlan.subKegiatan.pengaturan.create"), formAdd.value, {
            preserveScroll: true,
            onSuccess: () => {
                showModalAdd.value = false;
                selectedKegiatan.value = null;
                showNotifikasi("success", "Berhasil menambah sub kegiatan");
                formAdd.value = {
                    kode_kegiatan: "",
                    nama_kegiatan: "",
                    id_kegiatan: "",
                    nama_sub_kegiatan: "",
                    tahun: "",
                    kode_sub_kegiatan: "",
                };
                searchQuery.value = "";
            },
            onError: (err) => {
                if (err instanceof Error) {
                    errors.value = { general: err.message };
                } else if (typeof err === "object") {
                    errors.value = err as Record<string, string>;
                }
                showModalAdd.value = false;
                showNotifikasi("error", "Gagal menambah sub kegiatan");
                formAdd.value = {
                    kode_kegiatan: "",
                    nama_kegiatan: "",
                    id_kegiatan: "",
                    nama_sub_kegiatan: "",
                    tahun: "",
                    kode_sub_kegiatan: ''
                };
                selectedKegiatan.value = null;
                searchQuery.value = "";
            },
        });
    } catch (error) {
        console.error("Submission failed:", error);
    }
};

const selectKegiatan = (kegiatan: Kegiatan) => {
    selectedKegiatan.value = kegiatan;
    formAdd.value.id_kegiatan = kegiatan.id_kegiatan;
    searchQuery.value = "";
    router.replace({
        url: window.location.pathname, // Gunakan pathname tanpa query
        preserveState: true, // Pertahankan state saat ini
        preserveScroll: true // Pertahankan posisi scroll
    });
};

watch(
    newSub,
    (newValue: SubKegiatan) => {
        form.value = {
            id_sub_kegiatan: newValue.id_sub_kegiatan,
            kode_sub_kegiatan: newValue.kode_kegiatan,
            nama_sub_kegiatan: newValue.nama_kegiatan,
            id_kegiatan: newValue.id_kegiatan,
            nama_kegiatan: newValue.nama_kegiatan,
            tahun: newValue.tahun,
        };
    },
    { deep: true }
);

watch(() => showModalAdd.value, (newValue) => {
    if (!newValue) {
        router.replace({
            url: window.location.pathname,
            preserveState: true,
            preserveScroll: true
        });
    }
})
</script>

<template>

    <Head>
        <title>Master Sub Kegiatan</title>
    </Head>
    <div class="p-6">
        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-bold">Master Sub Kegiatan</h1>
            <div class="flex space-x-2 mb-6" v-if="roles">
                <Button @click="showImportModal = true"> Import Excel </Button>
                <Button @click="showModalAdd = true">
                    <Plus class="w-4 h-4 mr-2" /> Tambah Sub Kegiatan
                </Button>
            </div>
        </div>

        <div class="relative my-2">
            <div class="relative max-w-sm">
                <Input v-model="filters.search" placeholder="Cari kegiatan / sub kegiatan ..."
                    class="pl-10 pr-8 w-max-sm" />
                <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                <button v-if="filters.search" @click="filters.search = ''"
                    class="absolute right-2 top-2.5 text-muted-foreground hover:text-foreground transition-colors">
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>
        <div v-if="!subkegiatans.data.length" class="mb-6">
            <div class="p-6 text-center border rounded-lg bg-gray-50">
                <div class="mb-4 text-gray-500">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900">Data Kosong</h3>
                <p class="text-gray-500 mb-4">Sub kegiatan tidak ditemukan</p>
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
                    <TableRow v-for="row in subkegiatans.data" :key="row.kode_sub_kegiatan">
                        <TableCell :class="row.type !== 'sub' ? 'font-bold' : 'font-thin'">{{
                            row.kode_kegiatan
                        }}</TableCell>
                        <TableCell :class="row.type !== 'sub' ? 'font-bold text-cyan-950' : 'font-thin flex gap-2'
                            ">
                            <CornerDownRight v-if="row.type === 'sub'"
                                :class="row.type === 'sub' ? 'text-blue-800' : ''" class="w-4 h-4" />{{
                                    row.nama_kegiatan }}
                        </TableCell>
                        <TableCell :class="row.type !== 'sub' ? 'font-bold' : 'font-thin'">{{
                            row?.tahun
                        }}</TableCell>
                        <TableCell class="flex gap-2" v-if="row.type === 'sub' && roles">
                            <Button variant="outline" title="Edit" size="sm" @click="editKegiatan(row)">
                                <Edit class="w-4 h-4" color="#2865c8" />
                            </Button>
                            <Button variant="outline" title="Hapus" size="sm" @click="deleteKegiatan(row)">
                                <Trash2 class="w-4 h-4" color="#e7724b" />
                            </Button>
                            <Button variant="outline" title="Sub Kegiatan" size="sm" @click="openSubKeg(row)">
                                <ListTree class="w-4 h-4" />
                            </Button>
                        </TableCell>
                        <TableCell v-else>
                            <Button v-if="roles" variant="outline" title="Tambah Sub Kegiatan" size="sm"
                                @click="openAddSubDialog(row)">
                                <SquarePlus class="w-4 h-4" color="#2f9d50" />
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Pagination -->
            <div class="flex justify-center mt-4">
                <Pagination v-slot="{ page: currentPage }" :items-per-page="subkegiatans.per_page"
                    :total="subkegiatans.total" :sibling-count="1" :default-page="subkegiatans.current_page" show-edges
                    @update:page="(newPage) => router.visit(subkegiatans.path + '?page=' + newPage)">
                    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
                        <PaginationFirst :disabled="subkegiatans.current_page === 1" />
                        <PaginationPrev :disabled="!subkegiatans.prev_page_url" />

                        <template v-for="(item, index) in items">
                            <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value">
                                <Button class="w-10 h-10 p-0" :variant="subkegiatans.current_page === item.value ? 'default' : 'outline'
                                    " :disabled="!subkegiatans.links.find((l) => parseInt(l.label) === item.value)?.url
                                        ">
                                    {{ item.value }}
                                </Button>
                            </PaginationListItem>
                            <PaginationEllipsis v-else :key="index + '-'" />
                        </template>

                        <PaginationNext :disabled="!subkegiatans.next_page_url" />
                        <PaginationLast :disabled="subkegiatans.current_page === subkegiatans.last_page" />
                    </PaginationList>
                </Pagination>
            </div>
            <!-- Pagination -->
        </div>

        <!-- Add Modal sub Kegiatan baru  -->
        <Dialog v-model:open="showModalAdd">
            <DialogContent class="sm:max-w-[625px]">
                <DialogHeader>
                    <DialogTitle>Tambah Sub Kegiatan Baru</DialogTitle>
                    <DialogDescription>
                        Pilih kegiatan induk dan isi data sub kegiatan
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <!-- Search Input dengan Hasil Pencarian -->
                    <div class="grid grid-cols-4 items-start gap-4">
                        <Label for="search" class="text-right pt-2.5"> Cari Kegiatan Induk </Label>
                        <div class="col-span-3 relative">
                            <div class="relative">
                                <Input id="search" v-model="searchQuery" placeholder="Ketik kode/nama kegiatan..."
                                    class="pr-10" />
                                <Search class="absolute right-3 top-2.5 h-5 w-5 text-muted-foreground" />
                            </div>

                            <!-- Hasil Pencarian -->
                            <div v-if="isLoading"
                                class="absolute z-10 w-full mt-1 p-2 bg-background border rounded-md shadow-lg">
                                <div class="text-sm text-muted-foreground">Mencari...</div>
                            </div>

                            <div v-else-if="kegiatanOptions.length > 0 && searchQuery"
                                class="absolute z-10 w-full mt-1 bg-background border rounded-md shadow-lg max-h-60 overflow-y-auto">
                                <div v-for="kegiatan in kegiatanOptions" :key="kegiatan.id_kegiatan"
                                    class="p-2 hover:bg-accent cursor-pointer transition-colors"
                                    @click="selectKegiatan(kegiatan)">
                                    <div class="font-medium text-sm">{{ kegiatan.kode_kegiatan }}</div>
                                    <div class="text-xs text-muted-foreground truncate">
                                        {{ kegiatan.nama_kegiatan }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kegiatan Terpilih -->
                    <div v-if="selectedKegiatan" class="grid grid-cols-4 items-center gap-4">
                        <Label for="kegiatan" class="text-right"> Kode / Nama Kegiatan </Label>
                        <div class="col-span-3 space-y-1">
                            <div class="font-medium text-sm">{{ selectedKegiatan.kode_kegiatan }}</div>
                            <div class="text-xs text-muted-foreground">
                                {{ selectedKegiatan.nama_kegiatan }}
                            </div>
                        </div>
                    </div>

                    <!-- Form Input -->
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="id_subkeg" class="text-right"> Kode Sub </Label>
                        <div class="col-span-3 space-y-2">
                            <Input id="id_subkeg" v-model="formAdd.kode_sub_kegiatan"
                                placeholder="Contoh: 1.01.01.2.02.0001" />
                            <Alert v-if="errors.kode_sub_kegiatan" variant="destructive" class="text-xs py-1 px-2">
                                {{ errors.kode_sub_kegiatan }}
                            </Alert>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="nama" class="text-right"> Nama Sub </Label>
                        <div class="col-span-3 space-y-2">
                            <Input id="nama" v-model="formAdd.nama_sub_kegiatan"
                                placeholder="Masukkan nama sub kegiatan" />
                            <Alert v-if="errors.nama_sub_kegiatan" variant="destructive" class="text-xs py-1 px-2">
                                {{ errors.nama_sub_kegiatan }}
                            </Alert>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="showModalAdd = false"> Batal </Button>
                    <Button type="submit" @click="submitForm"
                        :disabled="!formAdd.kode_sub_kegiatan || !formAdd.nama_sub_kegiatan">
                        Simpan
                    </Button>
                </DialogFooter>
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

        <!-- Add Modal Tambah sub kegiatan -->
        <Dialog v-model:open="showAddSubDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle class="mb-4">Tambah Sub Kegiatan</DialogTitle>
                    <DialogDescription>
                        Kode / Nama Kegiatan :
                        <span class="font-semibold">
                            {{ parentKegiatan?.kode_kegiatan }} - {{ parentKegiatan?.nama_kegiatan }}
                        </span>
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="id_subkeg" class="text-right"> Kode Sub Kegiatan </Label>
                        <Input id="id_subkeg" v-model="newSub.kode_sub_kegiatan" class="col-span-3"
                            placeholder="Masukkan kode sub kegiatan" />
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="nama" class="text-right"> Nama Sub Kegiatan </Label>
                        <Input id="nama" v-model="newSub.nama_sub_kegiatan" class="col-span-3"
                            placeholder="Masukkan nama sub kegiatan" />
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="showAddSubDialog = false"> Batal </Button>
                    <Button type="submit" @click="submitSubKegiatan"
                        :disabled="!newSub.kode_sub_kegiatan || !newSub.nama_sub_kegiatan">
                        Simpan
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Edit Modal -->
        <Dialog v-model:open="showModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle class="mb-4">Edit Sub Kegiatan</DialogTitle>
                    <DialogDescription>
                        Kode / Nama Sub Kegiatan :
                        <span class="font-semibold">
                            {{ parentKegiatan?.kode_kegiatan }} - {{ parentKegiatan?.nama_kegiatan }}
                        </span>
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="id_subkeg" class="text-right"> Kode Sub Kegiatan </Label>
                        <Input id="id_subkeg" v-model="newSub.kode_kegiatan" class="col-span-3"
                            placeholder="Masukkan kode sub kegiatan" />
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="nama" class="text-right"> Nama Sub Kegiatan </Label>
                        <Input id="nama" v-model="newSub.nama_kegiatan" class="col-span-3"
                            placeholder="Masukkan nama sub kegiatan" />
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="showModal = false"> Batal </Button>
                    <Button type="submit" @click="submit" :disabled="!newSub.kode_kegiatan || !newSub.nama_kegiatan">
                        Simpan
                    </Button>
                </DialogFooter>
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
