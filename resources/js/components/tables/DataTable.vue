<template>
  <div>
    <!-- Desktop Table (hidden on mobile) -->
    <div class="hidden sm:block overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th v-for="column in columns" :key="column.key" class="px-2 sm:px-4 md:px-6 py-3 text-left text-xs sm:text-sm font-semibold text-gray-900">
              {{ column.label }}
            </th>
            <th v-if="hasActions" class="px-2 sm:px-4 md:px-6 py-3 text-left text-xs sm:text-sm font-semibold text-gray-900">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="row in rows" :key="row.id" class="hover:bg-gray-50">
            <td v-for="column in columns" :key="column.key" class="px-2 sm:px-4 md:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-900">
              {{ getNestedValue(row, column.key) }}
            </td>
            <td v-if="hasActions" class="px-2 sm:px-4 md:px-6 py-3 sm:py-4 text-xs sm:text-sm">
              <div class="flex space-x-2">
                <button
                  v-if="onEdit"
                  @click="onEdit(row)"
                  class="text-blue-600 hover:text-blue-900 text-xs"
                >
                  Edit
                </button>
                <button
                  v-if="onDelete"
                  @click="onDelete(row)"
                  class="text-red-600 hover:text-red-900 text-xs"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Mobile Card View (visible on mobile) -->
    <div class="sm:hidden space-y-3">
      <div v-for="row in rows" :key="row.id" class="bg-white rounded-lg shadow p-4 border-l-4 border-primary-600">
        <div v-for="column in columns" :key="column.key" class="flex justify-between items-start mb-3">
          <span class="font-semibold text-sm text-gray-700">{{ column.label }}</span>
          <span class="text-sm text-gray-900 text-right flex-1 ml-2">
            {{ getNestedValue(row, column.key) }}
          </span>
        </div>
        <div v-if="hasActions" class="flex space-x-2 mt-4 pt-4 border-t border-gray-200">
          <button
            v-if="onEdit"
            @click="onEdit(row)"
            class="flex-1 bg-blue-600 text-white py-2 rounded text-xs hover:bg-blue-700"
          >
            Edit
          </button>
          <button
            v-if="onDelete"
            @click="onDelete(row)"
            class="flex-1 bg-red-600 text-white py-2 rounded text-xs hover:bg-red-700"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  columns: {
    type: Array,
    required: true,
  },
  rows: {
    type: Array,
    required: true,
  },
  onEdit: Function,
  onDelete: Function,
});

const hasActions = computed(() => props.onEdit || props.onDelete);

const getNestedValue = (obj, path) => {
  return path.split('.').reduce((current, prop) => current?.[prop], obj);
};
</script>
