<template>
    <!-- Loading state while checking authentication -->
    <div v-if="auth.loading" class="start-page-loader" aria-label="Loading">
        <div class="spinner"></div>
    </div>

    <!-- Authenticated users see the onboarding/dashboard page -->
    <AuthenticatedStartPage v-else-if="auth.isAuthenticated" />

    <!-- Non-authenticated users see the landing page -->
    <GuestStartPage v-else />
</template>

<script setup>
import { inject } from 'vue'
import GuestStartPage from './GuestStartPage.vue'
import AuthenticatedStartPage from './AuthenticatedStartPage.vue'

const auth = inject('auth')
</script>

<style scoped>
.start-page-loader {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 5px solid #e0e0e0;
    border-top-color: #1976d2;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
