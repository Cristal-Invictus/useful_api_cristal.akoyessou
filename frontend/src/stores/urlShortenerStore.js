import { ref } from 'vue'
import { defineStore } from 'pinia'
import API from '../services/api'

export const useUrlShortenerStore = defineStore('urlShortener', () => {
  const links = ref([])
  const loading = ref(false)

  async function fetch() {
    loading.value = true
    try { const res = await API.get('/links'); links.value = res.data } finally { loading.value = false }
  }

  async function shorten(original_url, custom_code) {
    const res = await API.post('/shorten', { original_url, custom_code })
    links.value.unshift(res.data)
    return res.data
  }

  async function remove(id) {
    await API.delete(`/links/${id}`)
    links.value = links.value.filter(l => l.id !== id)
  }

  return { links, loading, fetch, shorten, remove }
})
