import { ref } from 'vue'
import { defineStore } from 'pinia'
import API from '../services/api'

export const useModulesStore = defineStore('modules', () => {
  const items = ref([])
  const loading = ref(false)
  const error = ref(null)

  async function fetch() {
    loading.value = true
    try {
      const res = await API.get('/modules')
      items.value = res.data
      return res.data
    } finally { loading.value = false }
  }

  async function activate(id) {
    await API.post(`/modules/${id}/activate`)
    return fetch()
  }

  async function deactivate(id) {
    await API.post(`/modules/${id}/deactivate`)
    return fetch()
  }

  return { items, loading, error, fetch, activate, deactivate }
})
