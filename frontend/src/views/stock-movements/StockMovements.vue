<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Stock Movements</h2>
        <p class="text-gray-500 mt-1">Track inventory changes and adjustments</p>
      </div>
      <button 
        @click="showModal = true"
        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 group"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Record Movement
      </button>
    </div>

    <!-- Movement History -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="text-xl font-bold text-gray-800">Movement History</h3>
        <div class="flex items-center space-x-2">
            <span class="flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">
                {{ movements.length }} total movements
            </span>
        </div>
      </div>
      
      <div class="divide-y divide-gray-100">
        <div v-if="isLoading" class="p-12 flex flex-col items-center justify-center space-y-3">
          <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
          <p class="text-gray-500 animate-pulse">Loading movements...</p>
        </div>
        
        <div v-else-if="movements.length === 0" class="p-12 text-center">
            <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No movements yet</h3>
            <p class="mt-1 text-gray-500">Inventory changes will be listed here.</p>
        </div>

        <div v-for="movement in movements" :key="movement.id" class="px-6 py-6 hover:bg-gray-50 transition-colors group">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <!-- Type Icon -->
              <div 
                :class="[
                  'w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-105',
                  movement.type === 'in' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'
                ]"
              >
                <svg v-if="movement.type === 'in'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
              </div>
              
              <div>
                <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                  {{ movement.product?.name || 'Unknown Product' }}
                </h4>
                <div class="flex items-center space-x-2 mt-0.5">
                  <span class="text-sm text-gray-500">{{ formatDate(movement.created_at) }}</span>
                  <span class="text-gray-300">•</span>
                  <span class="text-xs font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 uppercase tracking-tight">
                    {{ movement.product?.sku }}
                  </span>
                </div>
              </div>
            </div>
            
            <div class="text-right">
              <div :class="['text-2xl font-black', movement.type === 'in' ? 'text-green-600' : 'text-red-600']">
                {{ movement.type === 'in' ? '+' : '-' }}{{ movement.quantity }}
              </div>
              <div class="text-xs font-bold uppercase tracking-widest text-gray-400 mt-0.5">
                Stock {{ movement.type.toUpperCase() }}
              </div>
            </div>
          </div>
          
          <!-- Notes section -->
          <div v-if="movement.notes" class="mt-4 px-4 py-3 bg-gray-50 rounded-xl border border-gray-100/50 text-sm text-gray-600 flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <span class="italic font-medium">{{ movement.notes }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Record Movement Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="showModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
          <div class="bg-white p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-black text-gray-900" id="modal-title">Record Stock Movement</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="saveMovement" class="space-y-6">
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Product</label>
                <select 
                  v-model="form.product_id" 
                  required
                  class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 transition-all font-medium"
                >
                  <option value="" disabled>Select a product</option>
                  <option v-for="product in products" :key="product.id" :value="product.id">
                    {{ product.name }} ({{ product.sku }}) - Current: {{ product.quantity }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Movement Type</label>
                <div class="grid grid-cols-2 gap-4">
                  <button 
                    type="button"
                    @click="form.type = 'in'"
                    :class="[
                      'py-3 px-4 rounded-xl border-2 font-bold transition-all flex items-center justify-center',
                      form.type === 'in' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-gray-50 border-gray-100 text-gray-500 hover:bg-gray-100'
                    ]"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    Stock IN
                  </button>
                  <button 
                    type="button"
                    @click="form.type = 'out'"
                    :class="[
                      'py-3 px-4 rounded-xl border-2 font-bold transition-all flex items-center justify-center',
                      form.type === 'out' ? 'bg-red-50 border-red-500 text-red-700' : 'bg-gray-50 border-gray-100 text-gray-500 hover:bg-gray-100'
                    ]"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    Stock OUT
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Quantity</label>
                <input 
                  type="number" 
                  v-model="form.quantity" 
                  required 
                  min="1"
                  class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 transition-all font-medium"
                  placeholder="Enter amount"
                >
              </div>

              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Notes (Optional)</label>
                <textarea 
                  v-model="form.notes" 
                  rows="3"
                  class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 transition-all font-medium"
                  placeholder="e.g. Bulk sale, Restock, Return..."
                ></textarea>
              </div>

              <div class="pt-4 flex space-x-4">
                <button 
                  type="button" 
                  @click="showModal = false"
                  class="flex-1 px-4 py-4 border-2 border-gray-100 rounded-xl text-gray-500 font-bold hover:bg-gray-50 transition-all"
                >
                  Cancel
                </button>
                <button 
                  type="submit" 
                  :disabled="isSaving"
                  class="flex-1 px-4 py-4 bg-blue-600 rounded-xl text-white font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all disabled:opacity-50"
                >
                  <span v-if="isSaving">Recording...</span>
                  <span v-else>Record Movement</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../utils/axios'

const movements = ref([])
const products = ref([])
const isLoading = ref(true)
const isSaving = ref(false)
const showModal = ref(false)

const form = ref({
  product_id: '',
  type: 'in',
  quantity: 1,
  notes: ''
})

const fetchMovements = async () => {
  isLoading.value = true
  try {
    const res = await api.get('/stock-movements')
    movements.value = res.data
  } catch (err) {
    console.error(err)
  } finally {
    isLoading.value = false
  }
}

const fetchProducts = async () => {
  try {
    const res = await api.get('/products')
    products.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const saveMovement = async () => {
  isSaving.value = true
  try {
    await api.post('/stock-movements', form.value)
    await fetchMovements()
    await fetchProducts()
    showModal.value = false
    // Reset form
    form.value = {
      product_id: '',
      type: 'in',
      quantity: 1,
      notes: ''
    }
  } catch (err) {
    console.error(err)
  } finally {
    isSaving.value = false
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}

onMounted(() => {
  fetchMovements()
  fetchProducts()
})
</script>
