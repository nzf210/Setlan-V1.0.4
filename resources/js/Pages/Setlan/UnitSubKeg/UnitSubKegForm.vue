<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import InputError from "@/Components/InputError.vue";
import { Loader2, Option } from "lucide-vue-next";
import Combobox from "@/Pages/Setlan/Components/ComboBox.vue";

interface Option {
    label: string;
    value: string;
}

const props = defineProps<{
    data: any;
    kabs: any;
    years: any;
    subKegiatans: any;
    opds: Option[];
    units: any;
}>();

const emit = defineEmits(["success", "cancel"]);

const form = useForm({
    id_kab: props.data?.id_kab || "",
    id_opd: props.data?.id_opd || "",
    id_unit: props.data?.id_unit || "",
    tahun: props.data?.tahun || "",
    id_subkeg: props.data?.id_subkeg || "",
});

const submit = () => {
    const method = props.data ? "put" : "post";
    const url = props.data ? route("", props.data.id) : route("");

    form
        .transform((data) => ({
            ...data,
            _method: method === "put" ? "PUT" : "POST",
        }))
        .submit(method, url, {
            onSuccess: () => emit("success"),
            preserveScroll: true,
        });
};
const handleComboKab = (e: any) => {
    console.log("handleComboKab", e);
};
const handleComboOpd = (e: any) => {
    console.log("handleCombo OPD", e);
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div class="space-y-2">
            <Combobox link="setlan.gsV1.kabupaten" title="Kabupaten" id_select="" :is_edit="false" label_select=""
                @on-change="handleComboKab" />
            <InputError :message="form.errors.id_kab" />
        </div>

        <div class="space-y-2">
            <Combobox link="setlan.gsV1.opd" title="OPD" id_select="" :is_edit="false" label_select=""
                @on-change="handleComboOpd" />
            <InputError :message="form.errors.id_opd" />
        </div>
        <!--

        <div class="space-y-2">
            <Combobox :link="route('setlan.gsV1.kabupaten')" title="Unit" id_select="" :is_edit="false" label_select=""
                @on-change="handleComboKab" />
            <InputError :message="form.errors.tahun" />
        </div>

        <div class="space-y-2">
            <Combobox :link="route('setlan.gsV1.kabupaten')" title="Sub Kegiatan" id_select="" :is_edit="false"
                label_select="" @on-change="handleComboKab" />
            <InputError :message="form.errors.tahun" />
        </div> -->

        <div class="flex justify-end gap-2 mt-6">
            <Button type="button" variant="outline" @click="emit('cancel')" :disabled="form.processing">
                Batal
            </Button>
            <Button type="submit" :disabled="form.processing">
                <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                {{ data ? "Update" : "Simpan" }}
            </Button>
        </div>
    </form>
</template>
