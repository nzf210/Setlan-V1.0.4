<script setup lang="ts">
import { computed, watch } from "vue";

const props = defineProps<{
    msg?: string;
    id: string;
    modelValue: number | string;
    value?: any;
}>();

const emit = defineEmits(["update:modelValue", "inputValueChange"]);

watch(
    () => props.value,
    (nv) => {
        console.log("logs", nv);
    }
);

const inputValue = computed({
    get: () => props.modelValue,
    set: (value: number | string) => {
        emit("update:modelValue", value);
    },
});

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const value = target.value;
    inputValue.value = value;
    emit("inputValueChange", { id: props.id, value });
};
</script>

<template>
    <div>
        <div class="relative mt-2 rounded-md shadow-sm min-w-24">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-1">
                <span class="text-violet-700 sm:text-sm">{{ props.msg ?? "" }}</span>
            </div>

            <input type="number" :name="props.id" :id="props.id"
                class="block w-full rounded-md border-0 py-1.5 pl-6 pr-1 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                placeholder="0.00" v-model="inputValue" @input="handleInput" step="0.1" />
        </div>
    </div>
</template>
