import { ref } from 'vue'
import { defineStore } from 'pinia'
import API from '../services/api'

export const useWalletStore = defineStore('wallet', () => {
  const balance = ref(0)
  const transactions = ref([])
  const loading = ref(false)

  async function load() {
    loading.value = true
    try {
      const res = await API.get('/wallet')
      balance.value = res.data.balance || 0
      const t = await API.get('/wallet/transactions')
      transactions.value = t.data
    } finally { loading.value = false }
  }

  async function topup(amount) {
    const res = await API.post('/wallet/topup', { amount })
    await load()
    return res.data
  }

  async function transfer(payload) {
    const res = await API.post('/wallet/transfer', payload)
    await load()
    return res.data
  }

  return { balance, transactions, loading, load, topup, transfer }
})
