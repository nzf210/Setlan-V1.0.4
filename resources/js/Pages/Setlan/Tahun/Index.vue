<script setup lang="ts">
import { ref } from "vue";
import { useForm, router, Head } from "@inertiajs/vue3";
import {
    Calendar,
    Plus,
    Save,
    List,
    FolderX,
    Trash2,
    AlertCircle,
    CheckCircle,
    CalendarDays,
    Loader2,
} from "lucide-vue-next";

interface Tahun {
    id: number;
    year: number;
    akun: number;
    kd_id_barang: number;
    keg: number;
    sub_keg: number;
}

const props = defineProps<{
    tahun: Tahun[];
}>();

const daftarTahun = ref<Tahun[]>(props.tahun);
const tahunBaru = ref<string>("");
const updatingKegId = ref<number | null>(null);
const updatingSubKegId = ref<number | null>(null);
const updatingAkunId = ref<number | null>(null);
const deletingId = ref<number | null>(null);

const formTambah = useForm({
    year: "",
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

const tambahTahun = () => {
    formTambah.year = tahunBaru.value;
    formTambah.post(route("setlan.pengaturan.tahun.create"), {
        preserveScroll: true,
        onSuccess: () => {
            const newYear = {
                id: Math.max(...daftarTahun.value.map((y) => y.id)) + 1,
                year: parseInt(tahunBaru.value),
                akun: parseInt(tahunBaru.value),
                keg: parseInt(tahunBaru.value),
                sub_keg: parseInt(tahunBaru.value),
                kd_id_barang: 0,
            };
            daftarTahun.value.push(newYear);
            tahunBaru.value = "";
            formTambah.reset();
            showNotifikasi("success", "Tahun berhasil ditambahkan");
            router.reload({ only: ["years"] });
        },
        onError: () => {
            showNotifikasi("error", "Gagal menambahkan tahun");
        },
    });
};

const updateKodeBarang = async (tahun: Tahun) => {
    updatingKegId.value = tahun.id;
    const originalValue = tahun.kd_id_barang;

    try {
        console.log("tahun.keg", tahun.id);

        await router.patch(
            route("setlan.pengaturan.tahun.edit", { id: tahun.id }),
            {
                kd_id_barang: tahun.kd_id_barang,
            },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    showNotifikasi("success", "Tahun Kode Barang berhasil diperbarui");
                },
            }
        );
    } catch (error) {
        tahun.keg = originalValue;
        showNotifikasi("error", `Gagal memperbarui Tahun Kode Barang: ${error}`);
    } finally {
        updatingKegId.value = null;
    }
};

const updateSubKegiatan = async (tahun: Tahun) => {
    updatingSubKegId.value = tahun.id;
    const originalValue = tahun.sub_keg;

    try {
        await router.patch(
            route("setlan.pengaturan.tahun.edit", { id: tahun.id }),
            {
                sub_keg: tahun.sub_keg,
                keg: tahun.sub_keg,
            },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    showNotifikasi("success", "Tahun Sub Kegiatan berhasil diperbarui");
                },
            }
        );

        showNotifikasi("success", "Tahun Sub Kegiatan berhasil diperbarui");
    } catch (error) {
        tahun.sub_keg = originalValue;
        showNotifikasi("error", `Gagal memperbarui Tahun Sub Kegiatan: ${error}`);
    } finally {
        updatingSubKegId.value = null;
    }
};

const updateAkun = (tahun: Tahun) => {
    updatingAkunId.value = tahun.id;
    const originalValue = tahun.akun;

    try {
        router.patch(
            route("setlan.pengaturan.tahun.edit", { id: tahun.id }),
            {
                akun: tahun.akun,
            },
            {
                onSuccess: () => {
                    showNotifikasi("success", "Tahun Akun berhasil diperbarui");
                },
                preserveScroll: true,
                preserveState: true,
            }
        );
    } catch (error) {
        tahun.akun = originalValue;
        showNotifikasi("error", `Gagal memperbarui Tahun Akun: ${error}`);
    } finally {
        updatingAkunId.value = null;
    }
};

const hapusTahun = (id: number) => {
    if (!confirm("Apakah Anda yakin ingin menghapus tahun ini?")) return;

    deletingId.value = id;
    const index = daftarTahun.value.findIndex((y) => y.id === id);
    const deletedYear = { ...daftarTahun.value[index] };
    daftarTahun.value.splice(index, 1);

    try {
        router.delete(route("setlan.pengaturan.tahun.delete", id), {
            preserveScroll: true,
            onSuccess: () => {
                showNotifikasi("success", "Tahun berhasil dihapus");
            },
        });
    } catch (error) {
        console.error("Error deleting tahun:", error);
        showNotifikasi("error", `Gagal menghapus tahun: ${error}`);
        daftarTahun.value.splice(index, 0, deletedYear);
    } finally {
        deletingId.value = null;
    }
};
</script>

<template>

    <Head>
        <title>Pengaturan Tahun</title>
    </Head>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center justify-center gap-2">
                <Calendar class="w-6 h-6" />
                Pengaturan Tahun
            </h2>
            <p class="text-gray-600 mt-1">
                Kelola tahun untuk akun, kegiatan, dan sub kegiatan
            </p>
        </div>

        <!-- Form Tambah Tahun -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                    <Plus class="w-5 h-5" />
                    Tambah Tahun Baru
                </h3>
            </div>
            <div class="p-6">
                <form @submit.prevent="tambahTahun" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="w-full sm:w-auto flex-grow">
                        <label for="tahun" class="text-sm font-medium text-gray-700 mb-1 items-center gap-2 flex">
                            <CalendarDays class="w-4 h-4" />
                            Tahun
                        </label>
                        <input type="number" id="tahun" v-model="tahunBaru" min="2000" max="2100" required
                            placeholder="Masukkan tahun (contoh: 2024)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            :disabled="formTambah.processing" />
                        <p v-if="formTambah.errors.year" class="mt-2 text-sm text-red-600">
                            {{ formTambah.errors.year }}
                        </p>
                    </div>
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                        :disabled="formTambah.processing">
                        <template v-if="!formTambah.processing">
                            <Save class="w-4 h-4 mr-2" />
                            Simpan Tahun
                        </template>
                        <template v-else>
                            <Loader2 class="w-4 h-4 mr-2 animate-spin" />
                            Menyimpan...
                        </template>
                    </button>
                </form>
            </div>
        </div>
        <!-- Daftar Tahun -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                    <List class="w-5 h-5" />
                    Daftar Tahun Tersedia
                </h3>
            </div>
            <div class="p-6">
                <div v-if="daftarTahun.length === 0"
                    class="text-center py-8 text-gray-500 flex flex-col items-center gap-2">
                    <FolderX class="w-8 h-8" />
                    <p>Belum ada tahun yang tersedia</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tahun
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tahun Kode Barang
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tahun Sub Kegiatan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tahun Akun
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="tahun in daftarTahun" :key="tahun.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ tahun.year }}
                                </td>

                                <!-- Tahun Kode Barang -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <select v-model="tahun.kd_id_barang" @change="updateKodeBarang(tahun)"
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                            :disabled="updatingKegId === tahun.id">
                                            <option v-for="opt in daftarTahun" :key="'keg-' + opt.id" :value="opt.year">
                                                {{ opt.year }}
                                            </option>
                                        </select>
                                        <Loader2 v-if="updatingKegId === tahun.id"
                                            class="absolute right-3 top-2.5 h-4 w-4 animate-spin text-gray-400" />
                                    </div>
                                </td>

                                <!-- Tahun Sub Kegiatan -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <select v-model="tahun.sub_keg" @change="updateSubKegiatan(tahun)"
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                            :disabled="updatingSubKegId === tahun.id">
                                            <option v-for="opt in daftarTahun" :key="'sub-' + opt.id" :value="opt.year">
                                                {{ opt.year }}
                                            </option>
                                        </select>
                                        <Loader2 v-if="updatingSubKegId === tahun.id"
                                            class="absolute right-3 top-2.5 h-4 w-4 animate-spin text-gray-400" />
                                    </div>
                                </td>

                                <!-- Tahun Akun -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <select v-model="tahun.akun" @change="updateAkun(tahun)"
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                            :disabled="updatingAkunId === tahun.id">
                                            <option v-for="opt in daftarTahun" :key="'akun-' + opt.id"
                                                :value="opt.year">
                                                {{ opt.year }}
                                            </option>
                                        </select>
                                        <Loader2 v-if="updatingAkunId === tahun.id"
                                            class="absolute right-3 top-2.5 h-4 w-4 animate-spin text-gray-400" />
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button @click="hapusTahun(tahun.id)"
                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 disabled:opacity-50"
                                        title="Hapus tahun" :disabled="deletingId === tahun.id">
                                        <Trash2 v-if="deletingId !== tahun.id" class="w-4 h-4" />
                                        <Loader2 v-else class="w-4 h-4 animate-spin" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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
