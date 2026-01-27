<template>
  <div :class="['bg-white rounded-lg shadow-md p-4 sm:p-6', className]">
    <div v-if="title" class="mb-3 sm:mb-4">
      <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ title }}</h3>
      <p v-if="subtitle" class="text-xs sm:text-sm text-gray-600 mt-1">{{ subtitle }}</p>
    </div>
    <slot />
  </div>
</template>

<script setup>
defineProps({
  title: String,
  subtitle: String,
  className: String,
});
</script>
