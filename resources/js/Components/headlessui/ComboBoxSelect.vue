<script setup lang="ts">
import { ref, computed, watch } from "vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";
import { useDebouncedRef } from "@/Pages/Setlan/Helper";
import { masterBarangStore } from "@/store/MasterBarangStore";
import { comboSearch } from "@/store/ComboSearchStore";

interface Option {
    name: string;
    id: string;
    val1?: string;
    val2?: string;
    val3?: string;
}

const props = defineProps<{
    modelValue: any;
    loadOptions: Function;
    isUpdate?: boolean;
    index?: number;
    id: string;
    enable?: boolean;
}>();

const emit = defineEmits(["update:modelValue", "inputValueChange"]);
const master_barangStore = masterBarangStore();
const options = ref<Option[]>([]);
let query = ref<string>('');
const isLoading = ref(false);
const dbBounce = useDebouncedRef(query.value, 1000);

const combo_searchStore = comboSearch();

watch(query, (newVal) => {
    dbBounce.value = newVal;
    isLoading.value = true;
});

watch(
    [
        dbBounce,
        () => master_barangStore.id_barang,
        () => combo_searchStore.opdSelectCombo,
        () => combo_searchStore.unitSelectCombo,
    ],
    ([q]) => {
        if (props.loadOptions) {
            props.loadOptions(
                q,
                (result: any) => {
                    options.value = result;
                    if (
                        props.modelValue &&
                        !options.value.some((e: Option) => {
                            return e.id === props.modelValue?.id;
                        })
                    ) {
                        options.value.unshift(props.modelValue);
                    }
                    isLoading.value = false;
                },
                props.id
            );
        }
    },
    { immediate: true }
);

let filteredOptions = computed(() =>
    query.value === ""
        ? options.value
        : options.value.filter((data: Option) =>
            data.name
                .toLowerCase()
                .replace(/\s+/g, "")
                .includes(query.value.toLowerCase().replace(/\s+/g, ""))
        )
);

watch(
    () => props.modelValue,
    (nv) => {
        emit("inputValueChange", nv, props.id);
    }
);
</script>

<template>
    <div class="" :id="props.id + '_combo'">
        <Combobox as="div" class="-z-1-" :id="props.id + '_combobox'" by="id" :model-value="props.modelValue"
            @update:model-value="(id: any) => { $emit('update:modelValue', id); query = ''; isOpen = false; }" nullable
            :disabled="props.enable ? true : false">
            <div class="relative mt-1">
                <div
                    class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
                    <ComboboxInput
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0 z-30"
                        :displayValue="(person: any) => person.name" @change="query = $event.target.value" />
                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </ComboboxButton>
                </div>
                <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0"
                    @after-leave="query = ''" :id="props.id + '_transition'" class="-z-50">
                    <ComboboxOptions
                        class="z-10 absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                        :id="props.id + '_options'">
                        <div v-if="filteredOptions.length === 0 && !isLoading"
                            class="relative cursor-default select-none px-4 py-2 text-gray-700 z-50">
                            Data tidak ditemukan.
                        </div>
                        <div v-if="isLoading" class="relative cursor-default select-none px-4 py-2 text-gray-700">
                            Loading...
                        </div>

                        <template v-if="!isLoading">
                            <ComboboxOption v-for="option in filteredOptions" as="template" :key="option.id"
                                :value="option" v-slot="{ selected, active }" class="z-20">
                                <ul class="bg-slate-50 z-50">
                                    <li class="relative cursor-default select-none py-2 pl-10 pr-4 z-50" :class="{
                                        'bg-teal-600 text-white': active,
                                        'text-gray-900': !active,
                                    }">
                                        <span class="block truncate"
                                            :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                            {{ option.name }}
                                        </span>
                                        <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3"
                                            :class="{ 'text-white': active, 'text-teal-600': !active }">
                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </li>
                                </ul>
                            </ComboboxOption>
                        </template>
                    </ComboboxOptions>
                </TransitionRoot>
            </div>
        </Combobox>
    </div>
</template>
