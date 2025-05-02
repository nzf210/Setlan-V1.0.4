<script setup lang="ts">
import Swal from "sweetalert2";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { ref, watch } from "vue";
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
import { UploadIcon } from "lucide-vue-next";

interface KodeBarang {
    id: number;
    id_kd_barang: string;
    id_kab: string;
    tahun: number;
    nama: string;
    kab: {
        id_kab: string;
        nama_kab: string;
    };
}

interface PaginatedResult<KodeBarang> {
    data: KodeBarang[];
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
    kodeBarang: PaginatedResult<KodeBarang>;
    kabOptions: Array<{ id_kab: string; nama: string }>;
    years: Array<{ year: number }>;
}>();

const form = useForm({
    id: "",
    id_kd_barang: "",
    id_kab: "",
    tahun: new Date().getFullYear(),
    nama: "",
});

const showImportModal = ref(false);
const showTambahModal = ref(false);
const file = ref<File | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

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

const handleEdit = (id: KodeBarang) => {
    form.id = String(id.id);
    form.id_kd_barang = id.id_kd_barang;
    form.id_kab = id.kab.nama_kab;
    form.tahun = id.tahun;
    form.nama = id.nama;
    showTambahModal.value = true;
};
const submitEdit = () => {
    form.patch(route("setlan.pengaturan.edit"), {
        onSuccess: (page) => {
            showTambahModal.value = false;
            if (page.props.flash.error) {
                showNotifikasi("error", page.props.flash.error);
            } else {
                showNotifikasi("success", page.props.flash.success);
            }
        },
        onError: () => {
            showTambahModal.value = false;
            showNotifikasi("error", "Gagal memperbarui data");
        },
    });
};
const submitHapus = (id: number) => {
    Swal.fire({
        title: "Hapus",
        text: "Akan yakin menghapus data ini ?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "batal",
        confirmButtonText: "Ya, Hapus",
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete(route("setlan.pengaturan.hapus", { id: id }), {
                    onSuccess: (page) => {
                        Swal.fire({
                            toast: true,
                            icon: page.props.flash.success ? "success" : "error",
                            position: "top-end",
                            showConfirmButton: false,
                            title: page.props.flash.success
                                ? page.props.flash.success
                                : page.props.flash.error,
                            timer: 2500,
                        });
                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            } catch (error: unknown) {
                console.error("Error Hapus data:", error);
                Swal.fire("Error", "Gagal menghapus data : " + error, "error");
            }
        }
    });
};
const submitCreate = () => {
    console.log("form submitCreate", form);
    form.post(route("setlan.pengaturan.create"), {
        onSuccess: (page) => {
            showTambahModal.value = false;

            showNotifikasi("success", page.props.flash.success);
        },
        onError: (page) => {
            showTambahModal.value = false;
            showNotifikasi("error", page.props);
        },
    });
};

const page = ref(props.kodeBarang.current_page);

watch(showTambahModal, (newValue) => {
    if (!newValue) {
        form.reset();
    }
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
</script>

<template>

    <Head>
        <title>Kelola Kode Barang</title>
    </Head>

    <div class="p-6">
        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-bold">Kode Barang</h1>
            <div class="space-x-2">
                <Button @click="showImportModal = true"> Import Excel </Button>
                <Link method="post" :href="route('setlan.pengaturan.create')" as="button">
                <Button @click="showTambahModal = true" as="button">Tambah Baru</Button>
                </Link>
            </div>
        </div>

        <div v-if="!kodeBarang.data.length" class="mb-6">
            <div class="p-6 text-center border rounded-lg bg-gray-50">
                <div class="mb-4 text-gray-500">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900">Data Kosong</h3>
                <p class="text-gray-500 mb-4">Belum ada kode barang yang terdaftar</p>
                <Button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    @click="showTambahModal = true" as="button">
                    Tambah Baru
                </Button>
            </div>
        </div>
        <div v-else>
            <!-- Table -->
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Kode Barang</TableHead>
                        <TableHead>Kabupaten</TableHead>
                        <TableHead>Tahun</TableHead>
                        <TableHead>Nama Barang</TableHead>
                        <TableHead>Aksi</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="item in kodeBarang.data" :key="item.id">
                        <TableCell>{{ item?.id_kd_barang }}</TableCell>
                        <TableCell>{{ item?.kab?.nama_kab }}</TableCell>
                        <TableCell>{{ item?.tahun }}</TableCell>
                        <TableCell>{{ item?.nama }}</TableCell>
                        <TableCell class="space-x-2">
                            <Button @click="handleEdit(item)" variant="outline">Edit</Button>
                            <Button variant="destructive" @click="submitHapus(item.id)" :disabled="item.id === 1">
                                Hapus
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <!-- Pagination -->
            <div class="flex justify-center mt-4">
                <Pagination v-slot="{ page: currentPage }" :items-per-page="kodeBarang.per_page"
                    :total="kodeBarang.total" :sibling-count="2" :default-page="kodeBarang.current_page" show-edges
                    @update:page="(newPage) => router.visit(kodeBarang.path + '?page=' + newPage)">
                    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
                        <PaginationFirst :disabled="kodeBarang.current_page === 1" />
                        <PaginationPrev :disabled="!kodeBarang.prev_page_url" />

                        <template v-for="(item, index) in items">
                            <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value">
                                <Button class="w-10 h-10 p-0" :variant="kodeBarang.current_page === item.value ? 'default' : 'outline'
                                    " :disabled="!kodeBarang.links.find((l) => parseInt(l.label) === item.value)?.url
                    ">
                                    {{ item.value }}
                                </Button>
                            </PaginationListItem>
                            <PaginationEllipsis v-else :key="index + '-'" />
                        </template>

                        <PaginationNext :disabled="!kodeBarang.next_page_url" />
                        <PaginationLast :disabled="kodeBarang.current_page === kodeBarang.last_page" />
                    </PaginationList>
                </Pagination>
            </div>
            <!-- Pagination -->
        </div>
        <!-- Import Modal -->
        <Dialog v-model:open="showImportModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Import dari Excel</DialogTitle>
                </DialogHeader>

                <form @submit.prevent="form.post(route('setlan.pengaturan.import'))">
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

        <!-- Create/Edit Form -->
        <Dialog v-model:open="showTambahModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ form.id ? "Edit" : "Tambah" }} Kode Barang</DialogTitle>
                </DialogHeader>

                <form @submit.prevent="form.id ? submitEdit() : submitCreate()">
                    <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="id_kd_barang">Kode Barang</Label>
                            <Input id="id_kd_barang" v-model="form.id_kd_barang" class="col-span-3" />
                        </div>

                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="nama_barang">Nama Barang</Label>
                            <Input id="nama_barang" v-model="form.nama" class="col-span-3" />
                        </div>

                        <Button type="submit">Simpan</Button>
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
