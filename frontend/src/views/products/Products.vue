<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Products</h2>
        <p class="text-sm text-gray-500">Manage your product inventory</p>
      </div>
      <Button @click="openCreateModal">
        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Product
      </Button>
    </div>

    <!-- Search/Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
      <div class="relative w-full max-w-md">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input
          v-model="searchQuery"
          type="text"
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          placeholder="Search products by name or SKU..."
        />
      </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100 text-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.sku }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.category?.name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${{ Number(product.selling_price).toFixed(2) }}</div>
                <div class="text-xs text-gray-500">Cost: ${{ Number(product.purchase_price).toFixed(2) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 font-bold">{{ product.quantity }} units</div>
                <div class="text-xs text-gray-500">Min: {{ product.minimum_stock_level }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="product.quantity <= product.minimum_stock_level ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800'">
                  {{ product.quantity <= product.minimum_stock_level ? 'Low Stock' : 'In Stock' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="openEditModal(product)" class="text-blue-600 hover:text-blue-900 mr-4">
                  <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-900">
                  <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </td>
            </tr>
            <tr v-if="filteredProducts.length === 0">
              <td colspan="7" class="px-6 py-8 text-center text-gray-500">No products found matching your search.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model="isModalOpen" :title="isEditing ? 'Edit Product' : 'Add New Product'">
      <form class="space-y-4" @submit.prevent="saveProduct">
        <div class="grid grid-cols-2 gap-4">
          <Input label="Product Name *" v-model="form.name" required />
          <Input label="SKU *" v-model="form.sku" required />
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select v-model="form.category_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              <option :value="null">Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>

          <Input label="Purchase Price" type="number" step="0.01" v-model="form.purchase_price" />
          <Input label="Selling Price *" type="number" step="0.01" v-model="form.selling_price" required />
          <Input label="Current Quantity *" type="number" v-model="form.quantity" required />
          <Input label="Min Stock Level *" type="number" v-model="form.minimum_stock_level" required />
        </div>
      </form>

      <template #footer>
        <Button variant="secondary" @click="isModalOpen = false">Cancel</Button>
        <Button @click="saveProduct">{{ isEditing ? 'Save Changes' : 'Create Product' }}</Button>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../utils/axios'
import Button from '../../components/ui/Button.vue'
import Input from '../../components/ui/Input.vue'
import Modal from '../../components/ui/Modal.vue'

const products = ref([])
const categories = ref([])
const searchQuery = ref('')
const isModalOpen = ref(false)
const isEditing = ref(false)

const form = ref({
  id: null,
  name: '',
  sku: '',
  category_id: null,
  purchase_price: 0,
  selling_price: 0,
  quantity: 0,
  minimum_stock_level: 0
})

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value
  const lowerQuery = searchQuery.value.toLowerCase()
  return products.value.filter(p => 
    p.name.toLowerCase().includes(lowerQuery) || 
    p.sku.toLowerCase().includes(lowerQuery)
  )
})

const fetchProducts = async () => {
  try {
    const res = await api.get('/products')
    products.value = res.data
  } catch (e) {
    console.error(e)
  }
}

const fetchCategories = async () => {
  try {
    const res = await api.get('/categories')
    categories.value = res.data
  } catch (e) {
    console.error(e)
  }
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = {
    id: null, name: '', sku: '', category_id: null,
    purchase_price: 0, selling_price: 0, quantity: 0, minimum_stock_level: 0
  }
  isModalOpen.value = true
}

const openEditModal = (product) => {
  isEditing.value = true
  form.value = { ...product }
  isModalOpen.value = true
}

const saveProduct = async () => {
  try {
    if (isEditing.value) {
      await api.put(`/products/${form.value.id}`, form.value)
    } else {
      await api.post('/products', form.value)
    }
    isModalOpen.value = false
    await fetchProducts()
  } catch (e) {
    console.error(e)
    alert(e.response?.data?.message || 'Error saving product')
  }
}

const deleteProduct = async (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    try {
      await api.delete(`/products/${id}`)
      await fetchProducts()
    } catch (e) {
      console.error(e)
    }
  }
}

onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>
