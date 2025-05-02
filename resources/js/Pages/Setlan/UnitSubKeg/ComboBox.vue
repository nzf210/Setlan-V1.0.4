<script setup lang="ts">
import { computed, ref } from "vue";

interface Option {
    [key: string]: any;
}

interface Props {
    modelValue: string | number;
    options?: Option[]; // Jadikan optional
    labelKey: string;
    valueKey: string;
    label?: string;
    placeholder?: string;
    disabled?: boolean;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: "Pilih...",
    disabled: false,
    options: () => [], // Tambahkan default empty array
});

const emit = defineEmits<{
    (e: "update:modelValue", value: string | number): void;
}>();

const searchQuery = ref("");
const isOpen = ref(false);

const filteredOptions = computed(() => {
    if (!props.options) return []; // Handle undefined options
    if (!searchQuery.value) return props.options;
    return props.options.filter((option) =>
        String(option[props.labelKey]).toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectedLabel = computed(() => {
    if (!props.options) return props.placeholder; // Handle undefined options
    const selected = props.options.find(
        (opt) => String(opt[props.valueKey]) === String(props.modelValue)
    );
    return selected ? selected[props.labelKey] : props.placeholder;
});

const selectOption = (option: Option) => {
    emit("update:modelValue", option[props.valueKey]);
    isOpen.value = false;
    searchQuery.value = "";
};
</script>

<template>
    <div class="space-y-2">
        <label for="unit" v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>

        <div class="relative">
            <div class="flex items-center border rounded-md px-3 py-2">
                <input v-model="searchQuery" @click="isOpen = true" class="w-full outline-none bg-transparent"
                    :placeholder="selectedLabel" :disabled="disabled" />
            </div>

            <div v-if="isOpen"
                class="absolute z-10 w-full mt-1 bg-white border rounded-md shadow-lg max-h-60 overflow-auto">
                <div v-for="option in filteredOptions" :key="option[valueKey]" @click="selectOption(option)"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    {{ option[labelKey] }}
                </div>
            </div>
        </div>

        <InputError v-if="error" :message="error" />
    </div>
</template>
