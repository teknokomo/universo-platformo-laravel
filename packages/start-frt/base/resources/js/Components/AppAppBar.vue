<template>
    <nav class="app-bar">
        <div class="app-bar__container">
            <a href="/" class="app-bar__logo">
                <span class="app-bar__logo-text">Universo Platformo</span>
            </a>
            <div class="app-bar__actions">
                <template v-if="!auth.loading">
                    <button
                        v-if="auth.isAuthenticated"
                        class="btn btn--primary btn--small"
                        @click="handleLogout"
                    >
                        {{ t('nav.logout') }}
                    </button>
                    <a v-else href="/auth" class="btn btn--primary btn--small">
                        {{ t('nav.login') }}
                    </a>
                </template>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { inject } from 'vue'
import { useI18n } from '../Composables/useI18n.js'
import { logout } from '../Composables/useAuth.js'

const auth = inject('auth')
const { t } = useI18n()

async function handleLogout() {
    await logout()
    window.location.href = '/'
}
</script>

<style scoped>
.app-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    padding: 8px 0;
}

.app-bar__container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(24px);
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
}

.app-bar__logo {
    text-decoration: none;
    display: flex;
    align-items: center;
}

.app-bar__logo-text {
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.01em;
}

.app-bar__actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, opacity 0.2s;
}

.btn--primary {
    background: #1976d2;
    color: #fff;
}

.btn--primary:hover {
    background: #1565c0;
}

.btn--small {
    padding: 6px 16px;
    font-size: 0.875rem;
}
</style>
