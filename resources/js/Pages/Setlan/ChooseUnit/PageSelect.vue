<script setup lang="ts">
import ComboDate from "./ComboDate.vue";

import ComboSelect from "../../../Components/headlessui/ComboBoxSelect.vue";
import { comboSearch } from "@/store/ComboSearchStore";
import { watch, ref, computed } from "vue";
import { Kab, Opd } from "@/Pages/Setlan/Helper";

const props = defineProps<{
    kab: Kab[];
    opd: Opd[];
}>();

const kabSelect = ref();
const opdSelect = ref();
const unitSelect = ref();
const combo_searchStore = comboSearch();

const opdSelects = computed(() => combo_searchStore.opdSelectCombo);
const unitSelects = computed(() => combo_searchStore.unitSelectCombo);
const kabs = (e: string, setOptions: Function, props_id: string) => {
    setTimeout(() => {
        setOptions(
            props.kab.map((kab: Kab) => {
                return { id: kab.id_kab, name: kab.nama_kab };
            })
        );
    }, 70);
};

const listFrKabref = ref([]);
const opds = (e: string, setOptions: Function, props_id: string) => {
    setTimeout(() => {
        setOptions(opdSelects.value ?? []);
    }, 100);
};

const units = (e: string, setOptions: Function, props_id: string) => {
    setTimeout(() => {
        setOptions(unitSelects.value ?? []);
    }, 150);
};

watch(
    kabSelect,
    (nv) => {
        if (nv && nv != undefined && nv?.id) {
            localStorage.setItem("kabSelect", JSON.stringify(nv));
            combo_searchStore.setOpds({
                id_kab: nv?.id,
                nama_kab: nv?.name,
            });
            combo_searchStore.setisEnableOpd(false);
        }
    },
    { immediate: true }
);

watch(
    opdSelect,
    (nv) => {
        if (nv && nv != undefined && nv?.id) {
            localStorage.setItem("opdSelect", JSON.stringify(nv));
            combo_searchStore.setUnits({
                id_opd: nv?.id,
                nama_opd: nv?.name,
                id_kab: nv?.val1,
            });
            combo_searchStore.setisEnableUnit(false);
        }
    },
    { immediate: true }
);

watch(
    unitSelect,
    (nv) => {
        if (nv && nv != undefined && nv?.id) {
            localStorage.setItem("unitSelect", JSON.stringify(nv));
            combo_searchStore.setunit({
                id_unit: nv?.id,
                nama_unit: nv?.name,
                id_opd: nv?.val1,
            });
        }
    },
    { immediate: true }
);
</script>

<template>
    <div
        class="relative bg-gradient-to-br from-slate-600 to-pink-400 min-h-screen flex flex-col justify-center items-center">
        <div class="bg-slate-300 backdrop-blur-2xl opacity-70 rounded-md shadow-lg p-4 -mt-8 z-30">
            <h1 class="text-lg font-bold text-center text-purple-900 mb-2">PILIH UNIT</h1>
            <form class="space-y-4 w-80">
                <div class="relative" v-if="kab.length > 1">
                    <label class="block text-gray-700 font-semibold mb-2" for="kab">
                        Kabupaten
                    </label>
                    <ComboSelect :load-options="kabs" v-model="kabSelect" :id="'kab'" />
                </div>
                <div v-if="opd.length > 1">
                    <label class="block text-gray-700 font-semibold mb-2" for="opd">
                        Pilih OPD
                    </label>
                    <ComboSelect :load-options="opds" v-model="opdSelect" :id="'opd'"
                        :enable="combo_searchStore.isEnableOpd" />
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="unit">
                        Pilih Unit OPD
                    </label>
                    <ComboSelect :enable="combo_searchStore.isEnableUnit" :load-options="units" v-model="unitSelect"
                        :id="'unit'" />
                    <ComboDate />
                </div>
            </form>
        </div>
    </div>
</template>
