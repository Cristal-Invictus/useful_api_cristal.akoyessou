<script setup>
</script>

<template>
  <main class="home">
    <section class="hero">
      <h1>Welcome to your dashboard</h1>
      <p>Login or register to manage modules, wallet and short links.</p>
      <div class="actions">
        <router-link to="/register" class="btn">Register</router-link>
        <router-link to="/login" class="btn ghost">Login</router-link>
      </div>
    </section>
    <section class="features">
      <div class="card">Modules management</div>
      <div class="card">Wallet</div>
      <div class="card">URL Shortener</div>
      <div class="card">Time Tracker</div>
    </section>
  </main>
</template>

<style scoped>
.home{min-height:100vh;display:flex;flex-direction:column;gap:2rem;padding:4rem;background:#0f1720;color:#e6eef1}
.hero{max-width:900px;margin:0 auto;text-align:center}
.hero h1{font-size:2.2rem;margin-bottom:.6rem;color:#39d1a6}
.hero p{opacity:.9;margin-bottom:1rem}
.actions{display:flex;gap:1rem;justify-content:center}
.btn{background:#39d1a6;color:#062018;padding:.6rem 1rem;border-radius:6px;text-decoration:none}
.btn.ghost{background:transparent;border:1px solid rgba(255,255,255,.06);color:#cfeee4}
.features{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;max-width:1000px;margin:0 auto}
.card{background:rgba(255,255,255,0.03);padding:1rem;border-radius:8px;text-align:center}
</style>
