import axios from 'axios'

const API = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api',
  headers: { Accept: 'application/json' }
})

API.interceptors.request.use((c) => {
  try {
    const raw = localStorage.getItem('pinia')
    if (raw) {
      const parsed = JSON.parse(raw)
      const token = parsed?.auth?.token
      if (token) c.headers.Authorization = `Bearer ${token}`
    }
  } catch (e) {}
  return c
})

export default API
