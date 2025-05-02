<script setup lang="ts">
import Barang from "./Index.vue";
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

onMounted(() => {
  initFlowbite();
});
</script>

<template>
  <Barang> Masuk Barang </Barang>
</template>
