<script setup lang="ts">
import SetlanLayout from "@/Pages/Setlan/Components/Layouts.vue";
import HeadTitle from "@/Components/HeadTitle.vue";

defineProps<{
    title: string;
}>();
</script>

<template>
    <HeadTitle :title-head="title" />
    <SetlanLayout>
        <slot />
    </SetlanLayout>
</template>
