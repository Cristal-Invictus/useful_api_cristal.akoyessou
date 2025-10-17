# Évaluation Vue.js - Projet Frontend (9h–13h)

## Objectif

Implémentation minimaliste d'un frontend Vue + Pinia consommant l'API backend.

## Installation

cd frontend
npm install

## Variables

Créer `frontend/.env` si nécessaire:
VITE_API_URL=http://127.0.0.1:8000/api

## Démarrage

npm run dev

## Fichiers créés

src/services/api.js
src/stores/authStore.js
src/stores/modulesStore.js
src/stores/walletStore.js
src/stores/urlShortenerStore.js
src/stores/timeTrackerStore.js

## Pas à pas

1. Montrer `src/services/api.js` (client axios, injection automatique du token depuis localStorage)
2. Montrer `authStore` (login/register, stockage token)
3. Montrer `modulesStore` (fetch, activate, deactivate)
4. Montrer `walletStore`, `urlShortenerStore`, `timeTrackerStore` (actions principales)
5. Démontrer le flux: login -> activate module 1 -> shorten une URL -> lister
# frontend

This template should help get you started developing with Vue 3 in Vite.


## Customize configuration

See [Vite Configuration Reference](https://vite.dev/config/).

## Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```

### Run Unit Tests with [Vitest](https://vitest.dev/)

```sh
npm run test:unit
```

### Lint with [ESLint](https://eslint.org/)

```sh
npm run lint
```
