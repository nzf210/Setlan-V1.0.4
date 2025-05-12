<script lang="ts" setup>
import { ref, onMounted, watch } from "vue";
import Akuntansi from "./Index.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    TransitionChild,
} from "@headlessui/vue";
import { MinusIcon, PlusIcon } from "@heroicons/vue/20/solid";
import SearchIcon from "@/Pages/Setlan/Icons/SearchIcon.vue";
import XIcon from "@/Pages/Setlan/Icons/Xicon.vue";
import { } from "@/store/menuStore";
import ButtonAddakun from "@/Pages/Setlan/Barang/ButtonAddBarang.vue";
import { useDebouncedRef } from "@/Pages/Setlan/Helper";
import Swal from "sweetalert2";
import { Page } from "@/types";

const filterNamaAkun = useForm({
    nama_akun: "",
    kode_akun: "",
    tahun: 0,
});

const searchInput = ref<string>("");
const dbBounce = useDebouncedRef(searchInput.value, 500);

let query = ref([
    {
        id: "Akun",
        name: "Akun Permendagri",
        options: [{ value: "2l", label: "2L", checked: false }],
    },
]);

interface Akuns {
    checked?: boolean;
    id_akun: number;
    id_akun_aktif: number;
    kode_akun: string;
    kabupaten?: {
        id_kabupaten: string;
        nama_kabupaten: string;
    };
    nama_kabupaten: string;
    nama_akun: string;
    induk: Array<{
        id_akun: string;
        nama_akun: string;
    }>;
}

const akunRole = ref<string>("");
const selectedAkuns = ref<any>([]);
const selectAkuns = ref<Akuns | any>();

const akuns = ref<Akuns[]>();

const akunsAktif = ref<Akuns[]>();
const initialData = ref<any>();
const init = ref<object[]>([]);

watch(selectedAkuns, (newValue) => {
    akunsAktif.value = [...initialData.value, ...newValue];
});

watch(searchInput, (newVal) => {
    dbBounce.value = newVal;
});

watch(dbBounce, (newVal) => {
    searchNamaAkun(newVal);
});

const clearSearch = () => {
    searchInput.value = "";
};

onMounted(() => {
    searchNamaAkun();
});

const removeDraft = (id_akun: number) => {
    akunsAktif.value = [...removeByIds(akunsAktif.value, id_akun)];
    selectedAkuns.value = [...removeByIds(selectedAkuns.value, id_akun)];
};

function removeByIds(array: Akuns[] | any, id_akun: number) {
    return array.filter((item: Akuns) => item.id_akun !== id_akun);
}

function searchNamaAkun(e: string = "", t: boolean = false) {
    filterNamaAkun
        .transform((data: any) => ({
            ...data,
            nama_akun: e
        }))
        .get(route("setlan.akuntansi.akundata"), {
            preserveState: true,
            preserveScroll: true,
            async: true,
            onSuccess: (page) => {
                if (!page.props.flash?.value?.data) {
                    return;
                }
                selectAkuns.value = page.props.flash?.value?.data;
                akunRole.value = page.props.auth?.role;
                router.visit(
                    route("setlan.akuntansi.akunaktif"),
                    {
                        preserveState: true,
                        preserveScroll: true,
                        async: true,
                        onSuccess: (page: Page) => {
                            const akunaktif = page.props.flash?.value;
                            let akun = selectAkuns.value.map((akun: Akuns) => {
                                let checked: boolean = true;
                                return { ...akun, checked: checked };
                            });
                            let idsAkunAktif: any = [];
                            if (akunaktif && akunaktif.length > 0) {
                                idsAkunAktif = new Set(akunaktif.map((item: any) => item.id_akun));
                                akun = akun.map((item: Akuns) => ({
                                    ...item,
                                    checked: idsAkunAktif.has(item.id_akun) ? false : item.checked,
                                }));
                            }
                            akuns.value = akun;
                            init.value = akunaktif || [];
                            initialData.value = init.value;
                            akunsAktif.value = [...initialData.value];
                        },
                    }
                );
            },
        });
}

const createdAkunAktif = (e: any[]) => {
    const lengthAktif = e.length;
    Swal.fire({
        title: "Tambah Akun?",
        text: "Akan Menambah akun aktif sebanyak " + lengthAktif + " data",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "batal",
        confirmButtonText: "Ya, Tambah",
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.post(
                    route("setlan.akuntansi.akuncreate"),
                    {
                        akuns: e,
                    },
                    {
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
                            router.visit(route("setlan.akuntansi.akundata"));
                        },
                        preserveState: true,
                        preserveScroll: true,
                    }
                );
            } catch (error: unknown) {
                console.error("Error Menambah akun:", error);
                Swal.fire("Error", "Gagal buat akun: " + error, "error");
            }
        }
    });
};
const deleteAkunAktif = (id: number = 0, n: string = "") => {
    Swal.fire({
        title: "Hapus Akun !!!",
        text:
            "Apa anda yakin menghapus akun ini ? " +
            "Semua Barang yang tertaut akan terhapus " +
            "!!! " +
            n,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "batal",
        confirmButtonText: "Ya, Hapus",
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete(route("setlan.akuntansi.akundelete", { id: id }), {
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            Swal.fire({
                                toast: true,
                                icon: "success",
                                position: "top-end",
                                showConfirmButton: false,
                                title: page.props.flash.success,
                                timer: 2500,
                            });
                            router.visit(route("setlan.akuntansi.akundata"));
                        } else {
                            Swal.fire({
                                toast: true,
                                icon: "error",
                                position: "top-end",
                                showConfirmButton: false,
                                title: page.props.flash.error,
                                timer: 2500,
                            });
                        }

                    },
                    preserveState: true,
                    preserveScroll: true,
                });
            } catch (error: unknown) {
                Swal.fire("Masalah", "Maaf terjadi kesalahan" + error!);
            }
        }
    });
};
</script>

<template>
    <div>

        <Head>
            <title>Akun Aktif</title>
        </Head>
        <Akuntansi>
            <section class="text-gray-600 body-font rounded-md shadow-xl">
                <div class="container px-5 py-24 mx-auto flex flex-wrap">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <div class="w-full">
                            <form class="grid-cols-1 lg:ml-2 bg-slate-200">
                                <!-- price filter -->
                                <div class="flex items-center justify-between space-x-3">
                                    <div>
                                        <h3 class="text-lg font-semibold z-50 ml-2 py-2">
                                            Ref Akun Permendagri
                                        </h3>
                                    </div>
                                </div>
                                <Disclosure as="div" v-for="section in query" :key="section.id"
                                    class="border-b border-gray-300 py-6 px-2 transition-all duration-500 ease-in-out z-50"
                                    v-slot="{ open }">
                                    <h3 class="-my-3 flow-root z-50">
                                        <DisclosureButton
                                            class="flex w-full items-center justify-between bg-slate-50 py-3 text-sm text-gray-400 hover:text-gray-500">
                                            <span class="font-medium text-gray-900 px-2 z-50">{{
                                                section.name
                                            }}</span>
                                            <span class="ml-6 flex items-center">
                                                <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </DisclosureButton>
                                    </h3>
                                    <TransitionChild as="template"
                                        enter="transition ease-in-out duration-1000 transform"
                                        enter-from="-translate-y-14" enter-to="translate-y-0"
                                        leave="transition ease-in-out duration-1000 transform"
                                        leave-from="translate-y-0" leave-to="-translate-y-8">
                                        <DisclosurePanel class="pt-6 transition-all duration-300 ease-in-out">
                                            <!-- search -->
                                            <div class="relative">
                                                <input
                                                    class="appearance-none border-2 pl-10 border-gray-300 hover:border-gray-400 transition-colors rounded-md w-full py-2 px-3 text-gray-800 leading-tight focus:outline-none focus:ring-purple-600 focus:border-purple-600 focus:shadow-outline"
                                                    id="username" type="text" placeholder="Cari Akun..."
                                                    v-model="searchInput" />
                                                <div class="absolute right-0 inset-y-0 flex items-center"
                                                    @click="clearSearch">
                                                    <XIcon />
                                                </div>
                                                <div class="absolute left-0 inset-y-0 flex items-center">
                                                    <SearchIcon class="ml-2" />
                                                </div>
                                            </div>
                                            <!-- search -->

                                            <!-- CheckBOx -->
                                            <div
                                                class="w-full text-xl font-semibold ml-1 max-h-60 overflow-y-auto mt-1">
                                                <div class="flex items-center w-full space-x-2 rounded p-2 hover:bg-gray-100 accent-teal-600"
                                                    v-for="akun in akuns" :key="akun?.id_akun ?? ''">
                                                    <input :id="'input' + akun.id_akun" type="checkbox"
                                                        :name="String(akun.id_akun)" :value="akun"
                                                        :disabled="!akun.checked" v-model="selectedAkuns"
                                                        class="h-4 w-4 rounded border-gray-300 text-slate-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50 focus:ring-offset-0 disabled:cursor-not-allowed disabled:text-gray-400" />
                                                    <label :for="'input' + akun.id_akun"
                                                        class="flex w-full space-x-2 text-sm"
                                                        :class="!akun.checked ? 'text-gray-400' : 'text-gray-600'">
                                                        {{ akun.nama_akun }}
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- CheckBOx -->
                                        </DisclosurePanel>
                                    </TransitionChild>
                                </Disclosure>
                            </form>
                        </div>
                        <div class="w-full object-center rounded-lg md:mt-0 mt-12 md:col-span-2">
                            <div class="overflow-x-auto w-full max-h-[400px]">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kabupaten
                                            </th>
                                            <th scope="col"
                                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Akun | Kelompok
                                            </th>
                                            <th scope="col"
                                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider max-w-30">
                                                Jenis | Object
                                            </th>

                                            <th scope="col"
                                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Rincian
                                            </th>
                                            <th scope="col"
                                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sub Rincian
                                            </th>
                                            <th scope="col"
                                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                v-if="akunRole === 'admin_kab' || akunRole === 'super_admin'">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 max-h-60 overflow-y-scroll"
                                        v-if="selectAkuns && akunsAktif && akunsAktif.length > 0">
                                        <tr v-for="(akun, index) in akunsAktif" :key="index">
                                            <td class="px-2 py-2 whitespace-nowrap">
                                                <div v-if="!akun?.checked">{{ akun?.nama_kabupaten }}</div>
                                                <div v-else>Kab Draft</div>
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ akun?.induk[4]?.nama_akun }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ akun?.induk[3]?.nama_akun }}
                                                </div>
                                            </td>
                                            <td class="px-2 py-2 max-w-30">
                                                <div class="text-sm text-gray-900">
                                                    {{ akun?.induk[2]?.nama_akun }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ akun?.induk[1]?.nama_akun }}
                                                </div>
                                            </td>
                                            <td class="px-2 py-2 max-w-40 text-sm text-gray-500">
                                                {{ akun?.induk[0]?.nama_akun }}
                                            </td>
                                            <td class="px-2 py-2 text-sm text-gray-500 max-w-50">
                                                {{ akun.nama_akun }}
                                            </td>
                                            <td class="px-2 py-2 whitespace-nowrap">
                                                <span v-if="!akun.checked"
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 text-green-800">
                                                    Active
                                                </span>
                                                <span v-else
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-slate-500">
                                                    Draft
                                                </span>
                                            </td>
                                            <td v-if="akunRole === 'admin_kab' || akunRole === 'super_admin'"
                                                class="px-2 py-2 whitespace-nowrap text-sm font-medium">
                                                <button v-if="
                                                    (!akun.checked && akunRole === 'admin_kab') ||
                                                    akunRole === 'super_admin'
                                                " @click="deleteAkunAktif(akun.id_akun_aktif, akun.nama_akun)"
                                                    class="ml-2 text-red-600 hover:text-red-900">
                                                    Delete
                                                </button>
                                                <button v-else @click="removeDraft(akun.id_akun)"
                                                    class="ml-2 text-slate-400 hover:text-slate-500">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7" class="bg-blue-200 text-center py-4">
                                                <div class="text-center">Belum ditemukan akun aktif</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 flex justify-end" v-if="
                                (selectedAkuns &&
                                    selectedAkuns.length > 0 &&
                                    akunRole === 'admin_kab') ||
                                (selectedAkuns &&
                                    selectedAkuns.length > 0 &&
                                    akunRole === 'super_admin')
                            ">
                                <ButtonAddakun @button="createdAkunAktif(selectedAkuns)">
                                    Simpan
                                </ButtonAddakun>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </Akuntansi>
    </div>
</template>
