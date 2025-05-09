<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import {
    AlertCircle,
    FolderKanban,
    FileStack,
    Trash,
    CheckCircle,
    X,
} from "lucide-vue-next";
import { Alert, AlertTitle, AlertDescription } from "@/components/ui/alert";
import { router } from "@inertiajs/vue3";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";

import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import {
    Pagination,
    PaginationList,
    PaginationListItem,
    PaginationFirst,
    PaginationPrev,
    PaginationEllipsis,
    PaginationNext,
    PaginationLast,
} from "@/components/ui/pagination";

interface UnitSubKeg {
    id_kabupaten: number;
    nama_kabupaten?: string;
    id_opd: number;
    nama_opd: string;
    id_unit: number;
    nama_unit: string;
    id_sub_kegiatan_aktif: number;
    kode_kegiatan: string;
    nama_kegiatan: string;
    kode_sub_kegiatan: string;
    nama_sub_kegiatan: string;
    tahun: number;
    nomor_urut: number;
}

interface PaginatedResult<UnitSubKeg> {
    data: UnitSubKeg[];
    meta: {
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
}

const unitSubKegs = ref<UnitSubKeg[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

const props = defineProps<{
    data: PaginatedResult<UnitSubKeg>;
    role?: string | null;
}>();

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
    () => props.data,
    () => {
        unitSubKegs.value = props.data.data;
    }
);

const fetchData = async () => {
    try {
        error.value = null;
        loading.value = true;
        const response = props;
        unitSubKegs.value = response.data.data;
    } catch (err) {
        error.value = "Gagal memuat data. Silakan coba lagi.";
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchData();
});

const deteleUnitSubKeg = (id: number) => {
    if (confirm("Are you sure you want to delete this item?")) {
        router.delete(route("setlan.unitSubKeg.delete", id), {
            onSuccess: (page) => {
                fetchData();
                showNotifikasi("success", "Berhasil dihapus.");
            },
            onError: (error) => {
                showNotifikasi("error", "gagal dihapus.");
                console.error("Failed to delete:", error);
            },
        });
    }
};

const roles = props.role === "operator";
</script>

<template>
    <div class="max-w-6xl mx-auto p-6" v-if="unitSubKegs.length">
        <div v-if="loading" class="space-y-4">
            <div v-for="i in 5" :key="i" class="flex items-center space-x-4 p-4 border rounded-lg">
                <Skeleton class="h-4 w-[200px]" />
                <Skeleton class="h-4 w-[180px]" />
                <Skeleton class="h-4 w-[150px]" />
                <Skeleton class="h-4 w-[220px]" />
                <Skeleton class="h-4 w-[250px]" />
                <Skeleton class="h-4 w-[100px]" />
            </div>
        </div>

        <Alert v-else-if="error" variant="destructive">
            <AlertCircle class="w-4 h-4" />
            <AlertTitle>Error</AlertTitle>
            <AlertDescription>
                {{ error }}
                <Button variant="link" class="p-0 h-auto" @click="fetchData"> Coba Lagi </Button>
            </AlertDescription>
        </Alert>

        <div v-else class="rounded-md border">
            <Table>
                <TableHeader class="bg-muted">
                    <TableRow>
                        <TableHead>No</TableHead>
                        <TableHead>Kabupaten</TableHead>
                        <TableHead>OPD</TableHead>
                        <TableHead>Unit</TableHead>
                        <TableHead>Kegiatan</TableHead>
                        <TableHead>Sub Kegiatan</TableHead>
                        <TableHead class="w-[100px]">Tahun</TableHead>
                        <TableHead v-if="!roles">Aksi</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="item in unitSubKegs" :key="item.id_sub_kegiatan_aktif" class="hover:bg-muted/50">
                        <TableCell>{{ item?.nomor_urut || "-" }}</TableCell>
                        <TableCell>{{ item?.nama_kabupaten || "-" }}</TableCell>
                        <TableCell>{{ item?.nama_opd || "-" }}</TableCell>
                        <TableCell>{{ item?.nama_unit || "-" }}</TableCell>
                        <TableCell>
                            <div class="flex items-center gap-2">
                                <FolderKanban class="w-4 h-4 text-primary" />
                                {{ item?.nama_kegiatan || "-" }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <div class="flex items-center gap-2">
                                <FileStack class="w-4 h-4 text-emerald-600" />
                                {{ item?.nama_sub_kegiatan || "-" }}
                            </div>
                        </TableCell>
                        <TableCell>
                            <Badge variant="outline">
                                {{ item?.tahun }}
                            </Badge>
                        </TableCell>
                        <TableCell v-if="!roles">
                            <Trash variant="outline" class="w-4 h-4 text-red-400 cursor-pointer"
                                @click="deteleUnitSubKeg(item.id_sub_kegiatan_aktif)">
                            </Trash>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-4">
            <Pagination v-slot="{ page: currentPage }" :items-per-page="data?.meta?.per_page" :total="data?.meta?.total"
                :sibling-count="1" :default-page="data?.meta?.current_page" show-edges
                @update:page="(newPage) => router.visit(data?.meta?.path + '?page=' + newPage)">
                <PaginationList v-slot="{ items }" class="flex items-center gap-1">
                    <PaginationFirst :disabled="data?.meta?.current_page === 1" />
                    <PaginationPrev :disabled="!data?.meta?.prev_page_url" />

                    <ionPrate v-for="(item, index) in items">
                        <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value">
                            <Button class="w-10 h-10 p-0"
                                :variant="data?.meta?.current_page === item.value ? 'default' : 'outline'" :disabled="!data?.meta?.links.find((l) => parseInt(l.label) === item.value)?.url
                                    ">
                                {{ item.value }}
                            </Button>
                        </PaginationListItem>
                        <PaginationEllipsis v-else :key="index + '-'" />
                    </ionPrate>

                    <PaginationNext :disabled="!data?.meta?.next_page_url" />
                    <PaginationLast :disabled="data?.meta?.current_page === data?.meta?.last_page" />
                </PaginationList>
            </Pagination>
        </div>
        <!-- Pagination -->

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

<style scoped>
/* Optional custom styles */
</style>
