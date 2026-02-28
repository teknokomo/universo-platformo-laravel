<template>
    <div class="auth-page">
        <div class="auth-page__card">
            <h1 class="auth-page__title">Universo Platformo</h1>

            <!-- Tab: Login / Register -->
            <div class="auth-page__tabs">
                <button
                    class="auth-page__tab"
                    :class="{ 'auth-page__tab--active': mode === 'login' }"
                    @click="mode = 'login'"
                >
                    {{ t('auth.loginTab') }}
                </button>
                <button
                    class="auth-page__tab"
                    :class="{ 'auth-page__tab--active': mode === 'register' }"
                    @click="mode = 'register'"
                >
                    {{ t('auth.registerTab') }}
                </button>
            </div>

            <form class="auth-page__form" @submit.prevent="handleSubmit">
                <div class="auth-page__field">
                    <label class="auth-page__label" for="email">{{ t('auth.email') }}</label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        class="auth-page__input"
                        :placeholder="t('auth.emailPlaceholder')"
                        required
                        autocomplete="email"
                    />
                </div>

                <div class="auth-page__field">
                    <label class="auth-page__label" for="password">{{ t('auth.password') }}</label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        class="auth-page__input"
                        :placeholder="t('auth.passwordPlaceholder')"
                        required
                        autocomplete="current-password"
                        minlength="6"
                    />
                </div>

                <p v-if="errorMessage" class="auth-page__error" role="alert">{{ errorMessage }}</p>
                <p v-if="successMessage" class="auth-page__success" role="status">{{ successMessage }}</p>

                <button
                    type="submit"
                    class="btn btn--primary btn--full"
                    :disabled="isLoading"
                >
                    <span v-if="isLoading" class="btn__spinner"></span>
                    <span v-else>{{ mode === 'login' ? t('auth.loginButton') : t('auth.registerButton') }}</span>
                </button>
            </form>

            <p class="auth-page__back">
                <a href="/" class="auth-page__back-link">← {{ t('auth.backToHome') }}</a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, inject } from 'vue'
import { useI18n } from '../Composables/useI18n.js'
import { login, register } from '../Composables/useAuth.js'

const auth = inject('auth')
const { t } = useI18n()

const mode = ref('login')
const email = ref('')
const password = ref('')
const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)

async function handleSubmit() {
    errorMessage.value = ''
    successMessage.value = ''
    isLoading.value = true

    try {
        if (mode.value === 'login') {
            const result = await login(email.value, password.value)
            if (result.error) {
                errorMessage.value = result.error
            } else {
                window.location.href = '/'
            }
        } else {
            const result = await register(email.value, password.value)
            if (result.error) {
                errorMessage.value = result.error
            } else {
                successMessage.value = t('auth.registerSuccess')
                if (result.access_token) {
                    window.location.href = '/'
                }
            }
        }
    } catch (err) {
        errorMessage.value = t('auth.genericError')
        console.error('[AuthPage] submit error:', err)
    } finally {
        isLoading.value = false
    }
}
</script>

<style scoped>
.auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d47a1 0%, #1565c0 40%, #0a2744 100%);
    padding: 24px 16px;
}

.auth-page__card {
    background: #fff;
    border-radius: 16px;
    padding: 40px 32px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.auth-page__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
    text-align: center;
    margin: 0 0 24px;
}

.auth-page__tabs {
    display: flex;
    border-bottom: 2px solid #e0e0e0;
    margin-bottom: 24px;
}

.auth-page__tab {
    flex: 1;
    padding: 10px;
    background: none;
    border: none;
    font-size: 0.9375rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: color 0.2s, border-color 0.2s;
}

.auth-page__tab--active {
    color: #1976d2;
    border-bottom-color: #1976d2;
}

.auth-page__form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.auth-page__field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.auth-page__label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #333;
}

.auth-page__input {
    padding: 10px 14px;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    font-size: 0.9375rem;
    transition: border-color 0.2s;
    outline: none;
}

.auth-page__input:focus {
    border-color: #1976d2;
}

.auth-page__error {
    color: #d32f2f;
    font-size: 0.875rem;
    margin: 0;
    padding: 8px 12px;
    background: #fde8e8;
    border-radius: 6px;
}

.auth-page__success {
    color: #2e7d32;
    font-size: 0.875rem;
    margin: 0;
    padding: 8px 12px;
    background: #e8f5e9;
    border-radius: 6px;
}

.auth-page__back {
    text-align: center;
    margin: 20px 0 0;
    font-size: 0.875rem;
}

.auth-page__back-link {
    color: #1976d2;
    text-decoration: none;
}

.auth-page__back-link:hover {
    text-decoration: underline;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: background 0.2s, opacity 0.2s;
}

.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn--primary {
    background: #1976d2;
    color: #fff;
}

.btn--primary:hover:not(:disabled) {
    background: #1565c0;
}

.btn--full {
    width: 100%;
}

.btn__spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: inline-block;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
