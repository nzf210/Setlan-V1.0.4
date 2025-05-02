<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() =>
    props.active
        ? "inline-flex items-center justify-center py-2 px-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group"
        : "inline-flex items-center justify-center py-2  p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
);
</script>

<template>
    <ul>
        <li class="me-2">
            <Link :href="href" :class="classes">
            <slot />
            </Link>
        </li>
    </ul>
</template>
