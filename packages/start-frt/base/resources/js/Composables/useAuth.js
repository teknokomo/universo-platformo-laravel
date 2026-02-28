/**
 * useAuth - Authentication composable for start-frt package
 *
 * Provides reactive authentication state and auth operations
 * (login, register, logout) through the Laravel backend which
 * proxies requests to Supabase.
 *
 * The auth object is a singleton reactive store; provide it at the app root
 * so all components can inject it via inject('auth').
 */
import { reactive } from 'vue'
import axios from 'axios'

const API_BASE = '/api/v1/auth'

// Singleton reactive auth store
export const authStore = reactive({
    user: null,
    isAuthenticated: false,
    loading: true,
})

let initialized = false

/**
 * Initialize auth state by checking the current session.
 * Called once on application mount.
 */
export async function initializeAuth() {
    if (initialized) return
    initialized = true

    try {
        const response = await axios.get(`${API_BASE}/user`)
        authStore.user = response.data.user
        authStore.isAuthenticated = response.data.authenticated === true
    } catch {
        authStore.user = null
        authStore.isAuthenticated = false
    } finally {
        authStore.loading = false
    }
}

/**
 * Sign in with email and password.
 * @param {string} email
 * @param {string} password
 * @returns {Promise<{error: string|null}>}
 */
export async function login(email, password) {
    try {
        const response = await axios.post(`${API_BASE}/login`, { email, password })
        authStore.user = response.data.user
        authStore.isAuthenticated = true
        return { error: null, ...response.data }
    } catch (err) {
        const error = err.response?.data?.error ?? 'Login failed'
        return { error }
    }
}

/**
 * Register a new account.
 * @param {string} email
 * @param {string} password
 * @returns {Promise<{error: string|null}>}
 */
export async function register(email, password) {
    try {
        const response = await axios.post(`${API_BASE}/register`, { email, password })
        const data = response.data
        if (data.authenticated) {
            authStore.user = data.user
            authStore.isAuthenticated = true
        }
        return { error: null, ...data }
    } catch (err) {
        const error = err.response?.data?.error ?? 'Registration failed'
        return { error }
    }
}

/**
 * Sign out the current user.
 */
export async function logout() {
    try {
        await axios.post(`${API_BASE}/logout`)
    } catch {
        // Ignore errors on logout
    } finally {
        authStore.user = null
        authStore.isAuthenticated = false
    }
}
