import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/Login.vue'),
      meta: { guest: true }
    },
    {
      path: '/',
      component: () => import('../components/layout/AppLayout.vue'),
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          name: 'dashboard',
          component: () => import('../views/dashboard/Dashboard.vue')
        },
        {
          path: 'products',
          name: 'products',
          component: () => import('../views/products/Products.vue')
        },
        {
          path: 'categories',
          name: 'categories',
          component: () => import('../views/categories/Categories.vue'),
          meta: { role: 'Admin' }
        },
        {
          path: 'suppliers',
          name: 'suppliers',
          component: () => import('../views/suppliers/Suppliers.vue'),
          meta: { role: 'Admin' }
        },
        {
          path: 'sales',
          name: 'sales',
          component: () => import('../views/sales/Sales.vue')
        },
        {
          path: 'stock-movements',
          name: 'stock-movements',
          component: () => import('../views/stock-movements/StockMovements.vue')
        },
        {
          path: 'reports',
          name: 'reports',
          component: () => import('../views/reports/Reports.vue')
        }
      ]
    }
  ]
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' })
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next({ name: 'dashboard' })
  } else {
    // Check role based authorization
    if (to.meta.role && authStore.user?.role?.name !== to.meta.role) {
      next({ name: 'dashboard' }) // Redirect back to dashboard if not authorized
    } else {
      next()
    }
  }
})

export default router
