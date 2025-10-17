import { ref } from 'vue'
import { defineStore } from 'pinia'
import API from '../services/api'

export const useTimeTrackerStore = defineStore('timeTracker', () => {
  const sessions = ref([])
  const loading = ref(false)

  async function fetch() {
    loading.value = true
    try { const res = await API.get('/sessions'); sessions.value = res.data } finally { loading.value = false }
  }

  async function start(task_name, start_time) {
    const res = await API.post('/sessions/start', { task_name, start_time })
    await fetch()
    return res.data
  }

  async function stop(session_id, end_time) {
    const res = await API.post('/sessions/stop', { session_id, end_time })
    await fetch()
    return res.data
  }

  return { sessions, loading, fetch, start, stop }
})
