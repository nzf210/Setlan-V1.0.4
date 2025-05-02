<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import Combobox from "./ComboBox.vue";
import InputError from "@/Components/InputError.vue";
import ComboSetlan from "@/Pages/Setlan/Components/ComboBox.vue";
import { Loader2 } from "lucide-vue-next";
import Label from "@/components/ui/label/Label.vue";
import { PropType } from "vue";

interface Option {
    label: string;
    value: string;
}

const props = defineProps({
    data: Object,
    kabs: { type: Array as PropType<Option[]> },
    years: Array,
    subKegiatans: { type: Array as PropType<Option[]> },
    opds: { type: Array as PropType<Option[]> },
    units: { type: Array as PropType<Option[]> },
});

const form = useForm({
    _method: props.data ? "put" : "post",
    id_kab: props.data?.id_kab || "",
    id_opd: props.data?.id_opd || "",
    id_unit: props.data?.id_unit || "",
    tahun: props.data?.tahun || "",
    id_subkeg: props.data?.id_subkeg || "",
});

const handleComboKab = (e: any) => {
    console.log("handleComboKab", e);
};
</script>

<template>
    <form @submit.prevent="
        form._method
            ? form.put(route('unit-sub-kegiatan.update', props.data?.id))
            : form.post(route('unit-sub-kegiatan.store'))
        " class="space-y-6 max-w-3xl">
        <ComboSetlan :link="route('unit-sub-kegiatan.index')" title="Kabupaten" id_select="" :is_edit="false"
            label_select="" @on-change="handleComboKab" />

        <div class="space-y-2">
            <Label for="tahun">Tahun</Label>
            <Select v-model="form.tahun">
                <SelectTrigger>
                    <SelectValue placeholder="Pilih Tahun" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="year in years" :key="year" :value="year">
                        {{ year }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.tahun" />
        </div>

        <Combobox label="Sub Kegiatan" :options="subKegiatans" label-key="nama_subkeg" value-key="id_subkeg"
            v-model="form.id_subkeg" :error="form.errors.id_subkeg" />

        <Button type="submit" :disabled="form.processing">
            <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
            {{ form._method ? "Update" : "Simpan" }}
        </Button>
    </form>
</template>
