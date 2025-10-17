<template>
	<form @submit.prevent="doLogin">
		<input v-model="email" placeholder="email" />
		<input v-model="password" type="password" placeholder="password" />
		<button>Login</button>
	</form>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const email = ref('')
const password = ref('')
const router = useRouter()
const auth = useAuthStore()

async function doLogin() {
	await auth.login(email.value, password.value)
	router.push({ name: 'dashboard' })
}
</script>
