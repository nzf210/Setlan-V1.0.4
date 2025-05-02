<script setup lang="ts">
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
    ComboboxTrigger,
} from "@/components/ui/combobox";
import { Check, ChevronsUpDown, Search } from "lucide-vue-next";
import { ref, watch } from "vue";

const props = withDefaults(
    defineProps<{
        data: Array<{ value: string; label: string }>;
        isActive?: boolean;
        placeholder?: string;
    }>(),
    {
        isActive: false,
        placeholder: "Pilihan...",
    }
);

const emit = defineEmits<{
    (e: "change", value: string): void;
    (e: "update", value: string | undefined): void;
}>();

const value = ref<typeof props.data[0]>();

watch(
    () => value.value,
    (newValue) => {
        emit("update", newValue?.value);
    }
);
</script>

<template>
    <Combobox v-model="value" by="label" :disabled="!isActive">
        <ComboboxAnchor as-child>
            <ComboboxTrigger as-child>
                <Button variant="outline" class="justify-between">
                    {{ value?.label ?? "Pilih " + placeholder }}
                    <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                </Button>
            </ComboboxTrigger>
        </ComboboxAnchor>

        <ComboboxList>
            <div class="relative w-full max-w-sm items-center">
                <ComboboxInput class="pl-9 focus-visible:ring-0 border-0 border-b rounded-none h-10"
                    :placeholder="'Cari ' + placeholder" />
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                    <Search class="size-4 text-muted-foreground" />
                </span>
            </div>

            <ComboboxEmpty> tidak ditemukan... </ComboboxEmpty>

            <ComboboxGroup>
                <ComboboxItem v-for="dt in data" :key="dt.value" :value="dt">
                    {{ dt.label }}

                    <ComboboxItemIndicator>
                        <Check :class="cn('ml-auto h-4 w-4')" />
                    </ComboboxItemIndicator>
                </ComboboxItem>
            </ComboboxGroup>
        </ComboboxList>
    </Combobox>
</template>
