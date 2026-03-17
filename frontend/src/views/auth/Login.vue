<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Sign in to your account
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Smart Inventory Management System
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow-sm border border-gray-100 sm:rounded-xl sm:px-10">
        <form class="space-y-6" @submit.prevent="handleLogin">
          
          <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
            {{ errorMessage }}
          </div>

          <Input 
            label="Email address" 
            v-model="email" 
            type="email" 
            required 
            placeholder="admin@example.com" 
          />

          <Input 
            label="Password" 
            v-model="password" 
            type="password" 
            required 
            placeholder="••••••••" 
          />

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
              <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                Remember me
              </label>
            </div>
            <div class="text-sm">
              <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                Forgot your password?
              </a>
            </div>
          </div>

          <div>
            <Button type="submit" class="w-full flex justify-center" :disabled="isLoading">
              <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Sign in
            </Button>
          </div>

          <div class="mt-4 text-center text-xs text-gray-500 bg-gray-50 p-3 rounded border border-gray-100">
            <p class="font-semibold mb-1">Demo Credentials:</p>
            <p>Admin: admin@example.com / password123</p>
            <p>Staff: staff@example.com / password123</p>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import Input from '../../components/ui/Input.vue'
import Button from '../../components/ui/Button.vue'

const email = ref('')
const password = ref('')
const errorMessage = ref('')
const isLoading = ref(false)

const router = useRouter()
const authStore = useAuthStore()

const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''
  
  try {
    await authStore.login(email.value, password.value)
    router.push({ name: 'dashboard' })
  } catch (error) {
    if (error.response && error.response.status === 422) {
        errorMessage.value = error.response.data.message || 'Invalid credentials'
    } else {
        errorMessage.value = 'An error occurred during sign in.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>
