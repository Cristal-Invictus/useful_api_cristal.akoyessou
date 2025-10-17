<template>
	<div>
		<h2>Modules</h2>
		<ul>
			<li v-for="m in modules.items" :key="m.id">
				{{ m.name }}
				<button @click="toggle(m)">{{ m.active ? 'Off' : 'On' }}</button>
			</li>
		</ul>
		<h2>Short Links</h2>
		<div>
			<input v-model="url" placeholder="url" />
			<button @click="shorten">Shorten</button>
			<ul>
				<li v-for="l in links.links" :key="l.id">{{ l.code }} - {{ l.original_url }}</li>
			</ul>
		</div>
	</div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useModulesStore } from '@/stores/modulesStore'
import { useUrlShortenerStore } from '@/stores/urlShortenerStore'

const modules = useModulesStore()
const links = useUrlShortenerStore()
const url = ref('')

onMounted(() => { modules.fetch(); links.fetch() })

async function toggle(m) {
	if (m.active) await modules.deactivate(m.id)
	else await modules.activate(m.id)
}

async function shorten() { await links.shorten(url.value); url.value = '' }
</script>
