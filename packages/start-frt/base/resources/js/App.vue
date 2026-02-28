<template>
    <component :is="currentPage" />
</template>

<script setup>
import { computed, provide, onMounted } from 'vue'
import { authStore, initializeAuth } from './Composables/useAuth.js'
import StartPage from './Pages/StartPage.vue'
import AuthPage from './Pages/AuthPage.vue'

// Provide the reactive auth store to all child components
provide('auth', authStore)

// Determine which page to show based on the current URL path
const currentPage = computed(() => {
    const path = window.location.pathname
    if (path.startsWith('/auth')) return AuthPage
    return StartPage
})

// Initialize auth state on mount
onMounted(() => {
    initializeAuth()
})
</script>
