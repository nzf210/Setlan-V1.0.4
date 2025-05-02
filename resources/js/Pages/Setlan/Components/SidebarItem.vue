<script setup lang="ts">
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    to: string;
}>();

const navigateWithPreserveState = () => {
    router.visit(route(props.to || "#"), {
        preserveState: true,
    });
};
</script>

<style scoped>
.router-link-active,
.router-link-exact-active {
    background: #4c4c54;
}
</style>

<template>
    <button @click.prevent="navigateWithPreserveState"
        class="flex items-center gap-2 px-3 py-1 hover:bg-light-gold transition duration-200 cursor-pointer">
        <slot />
    </button>
</template>
