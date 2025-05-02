<script lang="ts" setup>
import type { HTMLAttributes } from "vue";
import { cn } from "@/lib/utils";
import { Link } from "@inertiajs/vue3";

const props = withDefaults(
  defineProps<{ as?: string; class?: HTMLAttributes["class"] }>(),
  {
    as: "a",
  }
);
</script>

<template>
  <Link
    :href="props.as"
    :class="cn('transition-colors hover:text-foreground', props.class)"
  >
    <slot />
  </Link>
</template>
