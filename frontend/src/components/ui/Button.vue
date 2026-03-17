<template>
  <button
    v-bind="$attrs"
    :type="type"
    class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
    :class="variantClasses"
  >
    <slot></slot>
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary' // primary, secondary, danger
  }
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'primary':
      return 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500'
    case 'secondary':
      return 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-blue-500'
    case 'danger':
      return 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500'
    default:
      return 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500'
  }
})
</script>
