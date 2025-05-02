<script setup lang="ts">
import { ref, watch } from "vue";
import Navbar from "./MenuDropdown.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const currentRoute = ref(route().current());

watch(
    () => page.url,
    () => {
        currentRoute.value = route().current();
    }
);
</script>

<template>
    <div class="flex flex-col top-0 relative">
        <div class="p-2 absolute w-full">
            <Navbar />
        </div>
        <slot />
    </div>
</template>
