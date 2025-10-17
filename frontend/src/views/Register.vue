<template>
	<form @submit.prevent="doRegister">
		<input v-model="name" placeholder="name" />
		<input v-model="email" placeholder="email" />
		<input v-model="password" type="password" placeholder="password" />
		<button>Register</button>
	</form>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const name = ref('')
const email = ref('')
const password = ref('')
const router = useRouter()
const auth = useAuthStore()

async function doRegister() {
	await auth.register(name.value, email.value, password.value)
	router.push({ name: 'login' })
}
</script>
