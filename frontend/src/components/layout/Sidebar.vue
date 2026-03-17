<template>
  <aside class="w-64 bg-white border-r border-gray-100 hidden md:flex flex-col h-full shadow-sm">
    <div class="flex items-center justify-center h-16 border-b border-gray-100 px-6 py-4">
      <div class="text-left w-full">
        <h1 class="text-xl font-bold text-gray-800">Inventory</h1>
        <p class="text-xs text-gray-500 font-medium">Management System</p>
      </div>
      <button class="text-gray-500 hover:text-gray-700 ml-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto mt-4">
      <router-link
        v-for="item in navigation"
        :key="item.name"
        :to="item.href"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-150"
        :class="[$route.name === item.name ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900']"
      >
        <component :is="item.icon" class="mr-3 h-5 w-5 flex-shrink-0" :class="[$route.name === item.name ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500']" aria-hidden="true" />
        {{ item.label }}
      </router-link>
    </nav>

    <div class="p-4 border-t border-gray-100 flex items-center mb-2">
      <div class="flex-1 truncate">
        <p class="text-sm font-medium text-gray-900 truncate">{{ authStore.user?.name || 'User' }}</p>
        <p class="text-xs text-blue-500 truncate cursor-pointer bg-blue-100 px-2 py-0.5 rounded-md inline-block mt-0.5">
          {{ authStore.user?.role?.name || 'Role' }}
        </p>
      </div>
      <button @click="logout" title="Logout" class="ml-2 text-red-500 hover:text-red-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const navigation = computed(() => {
  const items = [
    { name: 'dashboard', label: 'Dashboard', href: '/' },
    { name: 'products', label: 'Products', href: '/products' },
  ]

  if (authStore.isAdmin) {
    items.push({ name: 'categories', label: 'Categories', href: '/categories' })
    items.push({ name: 'suppliers', label: 'Suppliers', href: '/suppliers' })
  }

  items.push({ name: 'sales', label: 'Sales', href: '/sales' })
  items.push({ name: 'stock-movements', label: 'Stock Movements', href: '/stock-movements' })
  items.push({ name: 'reports', label: 'Reports', href: '/reports' })

  // Mock icons for simplicity, in production would use heroicons or similar properly mounted
  return items.map(item => ({ ...item, icon: 'svg' }))
})

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>
