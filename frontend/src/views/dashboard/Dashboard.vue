<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
        <p class="text-sm text-gray-500">Welcome back! Here's your business overview</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Products</p>
          <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.total_products }}</p>
        </div>
        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Today's Revenue</p>
          <p class="text-2xl font-bold text-gray-900 mt-1">${{ Number(stats.todays_revenue || 0).toFixed(2) }}</p>
        </div>
        <div class="p-3 bg-green-50 text-green-600 rounded-lg">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Low Stock Items</p>
          <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.low_stock_items }}</p>
        </div>
        <div class="p-3 bg-orange-50 text-orange-600 rounded-lg">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
          </svg>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Today's Sales</p>
          <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.todays_sales }}</p>
        </div>
        <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Low Stock Alert -->
    <div v-if="lowStockProducts.length > 0" class="bg-orange-50 rounded-xl p-6 border border-orange-100">
      <div class="flex items-center space-x-2 text-orange-800 mb-4">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-bold">Low Stock Alert</h3>
      </div>
      <p class="text-sm text-orange-700 mb-4">{{ lowStockProducts.length }} product(s) are running low on stock</p>
      
      <div class="bg-white rounded-lg shadow-sm border border-orange-100 divide-y divide-orange-50">
        <div v-for="product in lowStockProducts" :key="product.id" class="p-4 flex items-center justify-between">
          <div>
            <p class="font-medium text-gray-900">{{ product.name }}</p>
            <p class="text-xs text-gray-500">SKU: {{ product.sku }}</p>
          </div>
          <div class="text-right">
            <p class="font-bold text-red-600">{{ product.quantity }} units</p>
            <p class="text-xs text-gray-500">Min: {{ product.minimum_stock_level }}</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../utils/axios'

const stats = ref({
  total_products: 0,
  low_stock_items: 0,
  todays_sales: 0,
  todays_revenue: 0
})

const lowStockProducts = ref([])

onMounted(async () => {
  try {
    const resStats = await api.get('/reports/dashboard')
    stats.value = resStats.data

    const resLowStock = await api.get('/products/low-stock')
    lowStockProducts.value = resLowStock.data
  } catch (error) {
    console.error("Dashboard fetch error", error)
  }
})
</script>
