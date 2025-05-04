<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import Combobox from "./ComboBox.vue";
import { ref } from "vue";

interface KabOption {
    nama_kab: string;
    id_kab: string;
}
interface Props {
    filters: {
        id_kab?: string;
        id_opd?: string;
        id_unit?: string;
        tahun?: string;
    };
    kabs: KabOption[];
}

const props = defineProps<Props>();

const timeoutId = ref<number>();
const selectedKabupaten = ref("");
const updateFilter = (key: keyof Props["filters"], value: string) => {
    if (timeoutId.value) {
        clearTimeout(timeoutId.value);
    }

    timeoutId.value = setTimeout(() => {
        router.get(
            route("setlan.unitSubKeg"),
            { ...props.filters, [key]: value },
            { preserveState: true }
        );
    }, 300);
};
</script>

<template>
    <div class="grid grid-cols-4 gap-4 mb-6">
        <Combobox :options="$page.props.kabs as KabOption[]" label-key="nama_kab" value-key="id_kab"
            placeholder="Filter Kabupaten" v-model="selectedKabupaten"
            @update:modelValue="(val: string | number) => updateFilter('id_kabupaten', val as string)" />
    </div>

</template>
