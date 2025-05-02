<template>
    <Breadcrumb :items="items">
        <BreadcrumbList>
            <BreadcrumbItem v-for="(item, index) in items" :key="index">
                <BreadcrumbLink v-if="index !== items.length - 1" :href="item.url">
                    {{ item.name }}
                </BreadcrumbLink>
                <BreadcrumbPage v-else>
                    {{ item.name }}
                </BreadcrumbPage>
                <BreadcrumbSeparator v-if="index !== items.length - 1" />
            </BreadcrumbItem>
        </BreadcrumbList>
    </Breadcrumb>
</template>

<script setup lang="ts">
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";

defineProps<{ items: Array<{ name: string; url: string }> }>();
</script>
