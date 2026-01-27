<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 sm:p-0">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm sm:max-w-md lg:max-w-lg">
          <div class="flex justify-between items-center p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">{{ title }}</h2>
            <button
              @click="$emit('update:modelValue', false)"
              class="text-gray-400 hover:text-gray-600 p-1"
              aria-label="Close modal"
            >
              <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <div class="p-4 sm:p-6">
            <slot />
          </div>
          <div v-if="$slots.footer" class="flex justify-end gap-2 sm:gap-3 p-4 sm:p-6 border-t border-gray-200 flex-wrap">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({
  modelValue: Boolean,
  title: String,
});

defineEmits(['update:modelValue']);
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
