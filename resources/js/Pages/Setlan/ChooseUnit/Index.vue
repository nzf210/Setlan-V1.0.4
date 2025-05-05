<script setup lang="ts">
import { onMounted, ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import ComboSelect from "@/Pages/Setlan/Components/ComboSelect.vue";
import Button from "@/components/ui/button/Button.vue";
import Label from "@/components/ui/label/Label.vue";

import { useMenuStore } from "@/store/menuStore";
import { User } from "@/types";
const menuStrore = useMenuStore();

const { setNamaKabupaten, setNamaOpd, setNamaUnit, setTahun, setNamaUser } = menuStrore;

const props = defineProps<{
    user: User;
    kabupaten: Array<{ id_kabupaten: string; nama_kabupaten: string }>;
    opd: Array<{ id_opd: string; nama_opd: string }>;
    unit: Array<{ id_unit: string; nama_unit: string }>;
    title?: string;
    listopd?: Array<{ id_opd: string; nama_opd: string }>;
    tahun: Array<{ value: string; label: string }>;
    page?: {
        props: {
            auth: {
                user: {
                    name: string;
                };
            };
        };
    };
}>();

const kabSelect = ref<Array<{ id_kabupaten: string; nama_kabupaten: string }>>(
    props.kabupaten
);
const opdSelect = ref<Array<{ id_opd: string; nama_opd: string }>>(props.opd);
const unitSelect = ref<Array<{ id_unit: string; nama_unit: string }>>(props.unit);
const activeSelected = ref<{ id_kabupaten: string; id_opd: string; id_unit: string }>({
    id_kabupaten: "",
    id_opd: "",
    id_unit: "",
});

const selectKab = () => {
    return kabSelect.value.map(
        (kabupaten: { id_kabupaten: string; nama_kabupaten: string }) => {
            return { value: kabupaten.id_kabupaten, label: kabupaten.nama_kabupaten };
        }
    );
};
const selectOpd = () => {
    return opdSelect.value.map((kabupaten: { id_opd: string; nama_opd: string }) => {
        return { value: kabupaten.id_opd, label: kabupaten.nama_opd };
    });
};
const selectUnit = () => {
    return unitSelect.value.map((unit: { id_unit: string; nama_unit: string }) => {
        return { value: unit.id_unit, label: unit.nama_unit };
    });
};
const selectYear = () => {
    return props.tahun.map((tahun: { value: string; label: string }) => {
        return { value: tahun.value, label: tahun.label };
    });
};

const isActiveOpd = ref<boolean>(false);
const isActiveUnit = ref<boolean>(false);
const isActiveYear = ref<boolean>(false);

const labelKab = "Kabupaten";
const labelOpd = "O P D";
const labelUnit = "Unit";
const labelTahun = "Tahun";

onMounted(() => {
    if (props.user?.roles[0].name === "super_admin") {
        localStorage.setItem("list_kab", JSON.stringify(props.kabupaten));
    } else if (props.user?.roles[0].name === "admin_kab") {
        localStorage.setItem("list_opd", JSON.stringify(props.opd));
    } else {
        localStorage.setItem("list_unit", JSON.stringify(props.unit));
    }
});

const handleChangeKab = (value: { label: string; value: string }) => {
    activeSelected.value.id_kabupaten = value.value;
    setNamaKabupaten(value.label);
    router.get(
        route("setlan.getListOpd", { id: value.value }),
        {},
        {
            preserveState: true,
            onSuccess: (page) => {
                const listOpd = page.props.flash.opd;
                localStorage.setItem("list_opd", JSON.stringify(listOpd));
                opdSelect.value = listOpd;
                isActiveOpd.value = true;
            },
        }
    );
};
const handleChangeOpd = (value: { label: string; value: string }) => {
    setNamaOpd(value.label);
    if (props.user?.roles[0].name !== "super_admin") {
        setNamaKabupaten(props.kabupaten[0].nama_kabupaten);
    }
    router.get(
        route("setlan.getListUnit", { id: value.value }),
        {},
        {
            preserveState: true,
            onSuccess: (page) => {
                const listUnit = page.props.flash.unit;
                localStorage.setItem("list_unit", JSON.stringify(listUnit));
                unitSelect.value = listUnit;
                isActiveUnit.value = true;
            },
        }
    );
    activeSelected.value.id_opd = value.value;
};
const handleChangeUnit = (value: { label: string; value: string }) => {
    setNamaUnit(value.label);
    if (
        props.user?.roles[0].name !== "admin_kab" &&
        props.user?.roles[0].name !== "super_admin"
    ) {
        setNamaKabupaten(props.kabupaten[0].nama_kabupaten);
        setNamaOpd(props.opd[0].nama_opd);
    }
    activeSelected.value.id_unit = value.value;
    isActiveYear.value = true;
};
const handleChangeYear = (value: { label: string; value: string }) => {
    setTahun(value.label);
    setNamaUser(props.user.roles[0].name);
    router.post(
        route("setlan.setCookie"),
        {
            id_kabupaten: activeSelected.value.id_kabupaten,
            id_opd: activeSelected.value.id_opd,
            id_unit: activeSelected.value.id_unit,
            tahun: Number(value.value),
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const handleMasuk = () => {
    const savedState = localStorage.getItem("menuStoreState");
    if (savedState) {
        const parsedState = JSON.parse(savedState);
        const { tahun, namaKabupaten, namaUnit, namaOpd, namaUser } = parsedState;

        if (
            namaKabupaten === "" ||
            namaOpd === "" ||
            namaUnit === "" ||
            tahun === "" ||
            namaUser === ""
        ) {
            alert("Pilih Kabupaten, OPD, dan Unit terlebih dahulu");
            return;
        } else {
            router.get(
                route("setlan.getCookieStatus"),
                {},
                {
                    preserveState: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            router.visit(route("setlan.dashboard"));
                        }
                        if (page.props.flash.error) {
                            alert(page.props.flash.error);
                        }
                    },
                }
            );
        }
    } else {
        alert("Pilih Kabupaten, OPD, dan Unit terlebih dahulu");
    }
};
</script>

<template inheritAttrs="false">

    <Head>
        <title>Pilih Unit</title>
    </Head>
    <div class="flex flex-col mx-auto justify-center items-center h-screen opacity-95 px-3">
        <div v-if="user?.roles[0].name === 'super_admin'">
            <div class="bg-blue-300 py-4 px-6 rounded-md shadow-lg">
                <div class="max-w-md mx-auto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelKab
                        }}</label>
                    <ComboSelect :data="selectKab()" placeholder="Kabupaten..." @update:modelValue="handleChangeKab"
                        :is-active="true" />
                </div>
                <div class="max-w-md mx-auto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelOpd
                        }}</label>
                    <ComboSelect :data="selectOpd()" placeholder="OPD..." :is-active="isActiveOpd"
                        @update:modelValue="handleChangeOpd" />
                </div>
                <div class="max-w-md mx-auto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelUnit
                        }}</label>
                    <ComboSelect :data="selectUnit()" placeholder="Unit..." :is-active="isActiveUnit"
                        @update:modelValue="handleChangeUnit" />
                </div>
                <div class="max-w-md mx-auto">
                    <Label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelTahun
                        }}</Label>
                    <ComboSelect :data="selectYear()" placeholder="Tahun..." :is-active="isActiveYear"
                        @update:modelValue="handleChangeYear" />
                </div>
                <div class="mt-4 w-full flex">
                    <Button as-child class="mx-auto cursor-pointer" @click="handleMasuk">
                        <span class="text-white"> Masuk </span>
                    </Button>
                </div>
            </div>
        </div>
        <div v-if="user?.roles[0].name === 'admin_kab'">
            <div class="bg-blue-300 py-4 px-6 rounded-md shadow-lg">
                <div class="max-w-md mx-auto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelOpd
                        }}</label>
                    <ComboSelect :data="selectOpd()" placeholder="OPD..." :is-active="true"
                        @update:modelValue="handleChangeOpd" />
                </div>
                <div class="max-w-md">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelUnit
                        }}</label>
                    <ComboSelect :data="selectUnit()" placeholder="Unit..." :is-active="isActiveUnit"
                        @update:modelValue="handleChangeUnit" />
                </div>
                <div class="max-w-mabelUauto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelTahun
                        }}</label>
                    <ComboSelect :data="selectYear()" placeholder="Tahun..." :is-active="isActiveYear"
                        @update:modelValue="handleChangeYear" />
                </div>
                <div class="mt-4 w-full flex">
                    <Button as-child class="mx-auto cursor-pointer" @click="handleMasuk">
                        <span class="text-white"> Masuk </span>
                    </Button>
                </div>
            </div>
        </div>
        <div v-if="user?.roles[0].name === 'admin_opd'">
            <div class="bg-blue-300 py-4 px-6 rounded-md shadow-lg">
                <div class="max-w-md mx-auto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelUnit
                        }}</label>
                    <ComboSelect :data="selectUnit()" placeholder="Unit..." :is-active="true"
                        @update:modelValue="handleChangeUnit" />
                </div>
                <div class="max-w-md mx-auto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelTahun
                        }}</label>
                    <ComboSelect :data="selectYear()" placeholder="Tahun..." :is-active="isActiveYear"
                        @update:modelValue="handleChangeYear" />
                </div>
                <div class="mt-4 w-full flex">
                    <Button as-child class="mx-auto cursor-pointer" @click="handleMasuk">
                        <span class="text-white"> Masuk </span>
                    </Button>
                </div>
            </div>
        </div>
        <div v-if="user?.roles[0].name === 'operator'">
            <div class="bg-blue-300 py-4 px-6 rounded-md shadow-lg">
                <div class="max-w-md mx-auto">
                    <label for="" class="block text-sm font-medium leading-6 text-gray-900 p-4">{{
                        labelUnit
                        }}</label>
                    <ComboSelect :data="selectUnit()" placeholder="Unit..." :is-active="true"
                        @update:modelValue="handleChangeUnit" />
                </div>
                <div class="max-w-md mx-auto">
                    <Label for="" class="p-4">{{ labelTahun }}</Label>
                    <ComboSelect :data="selectYear()" placeholder="Tahun..." :is-active="isActiveYear"
                        @update:modelValue="handleChangeYear" />
                </div>
                <div class="mt-4 w-full flex">
                    <Button as-child class="mx-auto cursor-pointer" @click="handleMasuk">
                        <span class="text-white"> Masuk </span>
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
