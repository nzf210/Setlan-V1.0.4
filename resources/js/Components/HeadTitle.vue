<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
defineProps<{
    titleHead: string;
}>();
</script>

<template>

    <Head>
        <title>
            {{ titleHead }}
        </title>
    </Head>
</template>
