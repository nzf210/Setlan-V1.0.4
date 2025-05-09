<script setup lang="ts">
import HeadTitle from "@/Components/HeadTitle.vue";
import SetlanLayout from "@/Pages/Setlan/Components/Layouts.vue";

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
