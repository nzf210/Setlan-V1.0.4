<script setup lang="ts">
import { router } from "@inertiajs/vue3";

interface Links {
    first: string;
    last: string;
    next: string;
    prev: string;
}
interface Meta {
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}

const { meta } = defineProps<{
    meta: {
        links: Links;
        meta: Meta;
    };
}>();
</script>

<template>
    <nav aria-label="Page-navigation" class="z-30 flex">
        <ul class="inline-flex text-base h-6 mx-auto">
            <div class="flex flex-col items-center">
                <!-- Help text -->
                <span class="text-xs text-gray-700 dark:text-gray-400">
                    Menampilkan No
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ meta.meta.from + " " }} </span>-
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ meta.meta.to }}</span>
                    Dari
                    <span class="font-semibold text-gray-900 dark:text-white">{{
                        meta.meta.total
                        }}</span>
                    Data
                </span>
                <div class="inline-flex pt-1 xs:mt-0">
                    <!-- Buttons -->
                    <button class="btnPaginateL" @click="router.visit(meta.links.prev ?? '#')"
                        :disabled="!meta.links.prev">
                        <svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5H1m0 0 4 4M1 5l4-4" />
                        </svg>
                        Prev
                    </button>
                    <button class="btnPaginateL" @click="router.visit(meta.links.first ?? '#')"
                        :disabled="!meta.links.first">
                        Fisrt
                    </button>
                    <button class="btnPaginateR" @click="router.visit(meta.links.last ?? '#')"
                        :disabled="!meta.links.last">
                        Last
                    </button>
                    <button class="btnPaginateR" @click="router.visit(meta.links.next ?? '#')"
                        :disabled="!meta.links.next">
                        Next
                        <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </button>
                </div>
            </div>
        </ul>
    </nav>
</template>
