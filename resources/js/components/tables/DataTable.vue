<template>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50">
        <tr>
          <th v-for="column in columns" :key="column.key" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
            {{ column.label }}
          </th>
          <th v-if="hasActions" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <tr v-for="row in rows" :key="row.id" class="hover:bg-gray-50">
          <td v-for="column in columns" :key="column.key" class="px-6 py-4 text-sm text-gray-900">
            {{ getNestedValue(row, column.key) }}
          </td>
          <td v-if="hasActions" class="px-6 py-4 text-sm">
            <div class="flex space-x-2">
              <button
                v-if="onEdit"
                @click="onEdit(row)"
                class="text-blue-600 hover:text-blue-900"
              >
                Edit
              </button>
              <button
                v-if="onDelete"
                @click="onDelete(row)"
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
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
