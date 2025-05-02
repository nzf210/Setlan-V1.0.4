<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { onUnmounted, ref, watch } from "vue";
import ComboSelect from "@/Components/headlessui/ComboBoxSelect.vue";

const props = withDefaults(
    defineProps<{
        link: string;
        is_edit: boolean;
        title: string;
        id_select?: string | number;
        label_select?: string | number;
    }>(),
    {
        is_edit: false,
        title: "isi label",
        id_select: "",
        label_select: "",
    }
);
const requestQueue = new Map<string, AbortController>();
const emit: (event: "onChange", value: any) => void = defineEmits(["onChange"]);

const timeoutIdAkun = ref<number | null>(null);
const akunAktif = ref<{ id: string | number; name: string }>();

const loadAkunAktif = async (
    qry: string,
    setOptions: (options: { id: string | number; name: string }[]) => void
) => {
    const queueKey = props.link;

    if (requestQueue.has(queueKey)) {
        requestQueue.get(queueKey)?.abort();
    }

    if (timeoutIdAkun.value) clearTimeout(timeoutIdAkun.value);

    const controller = new AbortController();
    requestQueue.set(queueKey, controller);

    try {
        timeoutIdAkun.value = setTimeout(() => {
            router.visit(route(queueKey), {
                data: { search: qry },
                method: "get",
                preserveState: true,
                preserveScroll: true,
                async: true,
                onSuccess: (page) => {
                    const akunaktif =
                        (page.props.flash?.value?.data as { id: string | number; name: string }[]) ||
                        [];

                    // Ensure initial value exists in options for edit mode
                    if (props.is_edit && props.id_select && props.label_select) {
                        const exists = akunaktif.some((akun) => akun.id === props.id_select);
                        if (!exists) {
                            akunaktif.unshift({
                                id: props.id_select,
                                name: props.label_select.toString(),
                            });
                        }
                    }

                    // Set initial active value
                    if (props.is_edit && props.id_select && props.label_select) {
                        akunAktif.value = {
                            id: props.id_select,
                            name: props.label_select.toString(),
                        };
                    }

                    setOptions(
                        akunaktif.map((akun) => ({
                            id: akun.id,
                            name: akun.name,
                        }))
                    );
                },
                onError: (errors) => {
                    console.error("Error loading options:", errors);
                },
            });
        }, 100);
    } catch (error) {
        console.error("Error loading options:", error);
    } finally {
        requestQueue.delete(queueKey);
    }
};

onUnmounted(() => {
    if (timeoutIdAkun.value) clearTimeout(timeoutIdAkun.value);
});

watch(akunAktif, (newValue) => {
    if (newValue) emit("onChange", newValue);
});
</script>

<template>
    <div>
        <div v-if="Object.keys($page.props.errors).length > 0" class="mb-4 pb-4">
            <div v-for="(errorMessages, field) in $page.props.errors" :key="field" class="text-sm text-red-500">
                * {{ errorMessages }}
            </div>
        </div>

        <div class="relative w-full mb-5 group">
            <label :for="'rek-' + title"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white after:content-['*'] after:ml-0.5 after:text-red-500">
                {{ title }}
            </label>
            <ComboSelect :load-options="loadAkunAktif" v-model="akunAktif" :id="'rek-' + title" display-key="label" />
        </div>
    </div>
</template>

<style scoped>
.swal2-container {
    z-index: 2060 !important;
}
</style>
