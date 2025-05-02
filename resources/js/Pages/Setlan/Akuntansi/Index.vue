<script setup lang="ts">
import TabsAkuntansi from "@/Pages/Setlan/Akuntansi/TabsAkuntansi.vue";
</script>

<template>
    <div v-bind="$attrs">
        <div class="pt-5 bg-white/75">
            <TabsAkuntansi class="px-4 z-30" />
        </div>
        <div class="-z-50">
            <slot />
        </div>
    </div>
</template>
