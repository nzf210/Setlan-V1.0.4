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
import { comboSearch } from "@/store/ComboSearchStore";

const combo_search = comboSearch();

const dataDt: string[] = ["2024", "2025", "2026", "2027"];
const yearNow = new Date().getFullYear();

let selectedDt = ref<any>();

if (yearNow) {
    const dtLocal = localStorage.getItem("yearNow");
    if (dtLocal) {
        selectedDt = ref<string>(dtLocal);
    } else {
        selectedDt = ref<string>(yearNow.toString());
        localStorage.setItem("yearNow", yearNow.toString());
    }
}

watch([selectedDt], ([newValue]) => {
    combo_search.setYear(newValue);
    localStorage.setItem("yearNow", newValue);
});

let queryDt = ref("");
let query = ref("");

let filteredDt = computed<string[]>(() =>
    queryDt.value === ""
        ? dataDt
        : dataDt.filter((dt: string) =>
            dt
                .toLowerCase()
                .replace(/\s+/g, "")
                .includes(queryDt.value.toLowerCase().replace(/\s+/g, ""))
        )
);
</script>

<template>
    <div class="my-2">
        <p>Tahun</p>
    </div>
    <div>
        <div class="z-10">
            <Combobox v-model="selectedDt">
                <div class="relative mt-1">
                    <div
                        class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm focus-lift">
                        <ComboboxInput
                            class="w-full border-none bg-slate-200 focus:bg-slate-50 text-sm leading-5 text-gray-900 focus:ring-0"
                            @change="queryDt = $event.target.value" :displayValue="(year: any) => year!" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </ComboboxButton>
                    </div>
                    <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0"
                        @after-leave="query = ''">
                        <ComboboxOptions
                            class="z-50 absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                            <div v-if="filteredDt.length === 0 && query !== ''"
                                class="relative cursor-default select-none px-4 py-2 text-gray-700">
                                Tidak di temukan
                            </div>

                            <ComboboxOption v-for="year in filteredDt" as="template" :key="year" :value="year"
                                v-slot="{ selectedDt, active }">
                                <ul class="bg-slate-50 z-50">
                                    <li class="relative cursor-default select-none py-2 pl-10 pr-4 z-20" :class="{
                                        'bg-teal-600 text-white': active,
                                        'text-gray-900 bg-slate-50': !active,
                                    }">
                                        <span class="block truncate"
                                            :class="{ 'font-medium': selectedDt, 'font-normal': !selectedDt }">
                                            {{ year }}
                                        </span>
                                        <span v-if="selectedDt" class="absolute inset-y-0 left-0 flex items-center pl-3"
                                            :class="{ 'text-white': active, 'text-teal-600': !active }">
                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </li>
                                </ul>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </TransitionRoot>
                </div>
            </Combobox>
        </div>
    </div>
</template>
