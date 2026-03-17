import { defineStore } from 'pinia'
import api from '../utils/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role?.name === 'Admin',
    isStaff: (state) => state.user?.role?.name === 'Staff',
  },
  actions: {
    async login(email, password) {
      try {
        const response = await api.post('/login', { email, password })
        this.token = response.data.access_token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
        localStorage.setItem('user', JSON.stringify(this.user))
        return true
      } catch (error) {
        throw error
      }
    },
    async logout() {
      try {
        if (this.token) {
          await api.post('/logout')
        }
      } catch (e) {
        // ignore
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
    },
    async fetchUser() {
      try {
        const response = await api.get('/user')
        this.user = response.data
        localStorage.setItem('user', JSON.stringify(this.user))
      } catch (error) {
        this.logout()
      }
    }
  }
})
