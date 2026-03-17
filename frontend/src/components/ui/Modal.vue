<template>
  <Teleport to="body">
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div 
        class="absolute inset-0 bg-gray-900 bg-opacity-50 transition-opacity" 
        @click="$emit('update:modelValue', false)"
      ></div>
      <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ title }}
          </h3>
          <button @click="$emit('update:modelValue', false)" class="text-gray-400 hover:text-gray-500">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="px-6 py-4">
          <slot></slot>
        </div>
        <div v-if="$slots.footer" class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    default: 'Modal Title'
  }
})
defineEmits(['update:modelValue'])
</script>
