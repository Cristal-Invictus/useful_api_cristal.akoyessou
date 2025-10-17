import { ref } from 'vue'
import { defineStore } from 'pinia'
import API from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(null)
  const userId = ref(null)
  const loading = ref(false)
  const error = ref(null)

  async function login(email, password) {
    loading.value = true
    error.value = null
    try {
      const res = await API.post('/login', { email, password })
      token.value = res.data.token
      userId.value = res.data.user_id
      return res.data
    } catch (e) {
      error.value = e
      throw e
    } finally {
      loading.value = false
    }
  }

  async function register(name, email, password) {
    loading.value = true
    error.value = null
    try {
      const res = await API.post('/register', { name, email, password })
      return res.data
    } catch (e) {
      error.value = e
      throw e
    } finally {
      loading.value = false
    }
  }

  function logout() {
    token.value = null
    userId.value = null
    localStorage.removeItem('pinia')
  }

  return { token, userId, loading, error, login, register, logout }
})
