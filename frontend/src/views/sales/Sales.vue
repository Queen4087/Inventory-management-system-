<template>
  <div class="h-full flex flex-col md:flex-row gap-6">
    <!-- Products List (Left Side) -->
    <div class="flex-1 flex flex-col space-y-4">
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Point of Sale</h2>
      </div>

      <!-- Search -->
      <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input
          v-model="searchQuery"
          type="text"
          class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white"
          placeholder="Search products..."
        />
      </div>

      <!-- Product Grid -->
      <div class="flex-1 overflow-y-auto min-h-0 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div 
            v-for="product in filteredProducts" 
            :key="product.id"
            @click="addToCart(product)"
            class="border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-300 hover:shadow-md transition bg-white flex flex-col justify-between h-32"
          >
            <div>
              <h3 class="font-medium text-gray-900 text-sm line-clamp-2">{{ product.name }}</h3>
              <p class="text-xs text-gray-500 mt-1">{{ product.sku }}</p>
            </div>
            <div class="flex justify-between items-end mt-2">
              <span class="font-bold text-blue-600">${{ Number(product.selling_price).toFixed(2) }}</span>
              <span class="text-xs" :class="product.quantity > 0 ? 'text-green-600' : 'text-red-600'">
                {{ product.quantity > 0 ? `${product.quantity} in stock` : 'Out of stock' }}
              </span>
            </div>
          </div>
          <div v-if="filteredProducts.length === 0" class="col-span-full py-8 text-center text-gray-500">
            No products found
          </div>
        </div>
      </div>
    </div>

    <!-- Cart (Right Side) -->
    <div class="w-full md:w-96 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[calc(100vh-8rem)]">
      <div class="p-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Current Order</h3>
      </div>
      
      <div class="flex-1 overflow-y-auto p-4 space-y-4">
        <div v-if="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-500 text-sm">
          <svg class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          Cart is empty
        </div>

        <div v-for="item in cart" :key="item.product_id" class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
          <div class="flex-1 overflow-hidden pr-2">
            <p class="text-sm font-medium text-gray-900 truncate">{{ item.name }}</p>
            <p class="text-xs text-gray-500">${{ Number(item.price).toFixed(2) }} / unit</p>
          </div>
          <div class="flex items-center space-x-2">
            <button @click="updateCartQty(item, -1)" class="w-7 h-7 bg-white rounded-md border border-gray-200 flex items-center justify-center hover:bg-gray-100">-</button>
            <span class="text-sm font-medium w-6 text-center">{{ item.quantity }}</span>
            <button @click="updateCartQty(item, 1)" class="w-7 h-7 bg-white rounded-md border border-gray-200 flex items-center justify-center hover:bg-gray-100">+</button>
            <button @click="removeFromCart(item)" class="ml-2 text-red-500 hover:text-red-700">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="p-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
        <div class="flex justify-between items-center mb-4">
          <span class="text-gray-600">Total</span>
          <span class="text-2xl font-bold text-gray-900">${{ cartTotal.toFixed(2) }}</span>
        </div>
        <Button class="w-full py-3 text-lg" @click="checkout" :disabled="cart.length === 0 || isProcessing">
          {{ isProcessing ? 'Processing...' : 'Complete Sale' }}
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../utils/axios'
import Button from '../../components/ui/Button.vue'

const products = ref([])
const cart = ref([])
const searchQuery = ref('')
const isProcessing = ref(false)

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value
  const lowerQuery = searchQuery.value.toLowerCase()
  return products.value.filter(p => 
    p.name.toLowerCase().includes(lowerQuery) || 
    p.sku.toLowerCase().includes(lowerQuery)
  )
})

const cartTotal = computed(() => {
  return cart.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const fetchProducts = async () => {
  try {
    const res = await api.get('/products')
    products.value = res.data
  } catch (e) {
    console.error(e)
  }
}

const addToCart = (product) => {
  if (product.quantity <= 0) {
    alert('This product is out of stock!')
    return
  }

  const existing = cart.value.find(item => item.product_id === product.id)
  
  if (existing) {
    if (existing.quantity >= product.quantity) {
      alert(`Cannot add more. Only ${product.quantity} in stock.`)
      return
    }
    existing.quantity++
  } else {
    cart.value.push({
      product_id: product.id,
      name: product.name,
      price: product.selling_price,
      quantity: 1,
      max_stock: product.quantity
    })
  }
}

const updateCartQty = (item, diff) => {
  const newQty = item.quantity + diff
  if (newQty <= 0) {
    removeFromCart(item)
  } else if (newQty > item.max_stock) {
    alert(`Cannot add more. Only ${item.max_stock} in stock.`)
  } else {
    item.quantity = newQty
  }
}

const removeFromCart = (item) => {
  cart.value = cart.value.filter(i => i.product_id !== item.product_id)
}

const checkout = async () => {
  if (cart.value.length === 0) return
  isProcessing.value = true

  try {
    const payload = {
      items: cart.value.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity
      }))
    }
    
    await api.post('/sales', payload)
    alert('Sale completed successfully!')
    cart.value = []
    await fetchProducts() // Refresh stock levels
  } catch (error) {
    alert(error.response?.data?.message || 'Error processing checkout')
  } finally {
    isProcessing.value = false
  }
}

onMounted(fetchProducts)
</script>
