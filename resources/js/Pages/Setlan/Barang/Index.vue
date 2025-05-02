<script setup lang="ts">
import SetlanLayout from "@/Pages/Setlan/Index.vue";
import { Head } from "@inertiajs/vue3";
defineOptions({ layout: SetlanLayout });
</script>

<template>

    <Head>
        <title>Barang</title>
    </Head>
    <div class="flex flex-col sticky">
        <div class="">
            <slot />
        </div>
    </div>
</template>
