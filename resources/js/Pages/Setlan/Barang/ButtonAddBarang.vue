<script setup lang="ts">
const emit = defineEmits(["button"]);
const button = () => {
  emit("button");
};

const props = defineProps<{
  bg?: boolean,
}>();

</script>

<template>
  <button
    class="mt-0 inline-flex items-center   justify-center rounded-xl py-2 px-4 font-dm text-base font-thin text-white shadow-xl shadow-blue-400/75 transition-transform duration-200 ease-in-out hover:scale-[1.02]"
    :class="[!props.bg?'active:bg-blue-700 bg-blue-600':'']"
    href="#"
    @click.prevent="button"
  >
    <slot />
  </button>
</template>
