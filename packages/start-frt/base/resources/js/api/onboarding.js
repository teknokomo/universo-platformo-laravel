/**
 * Onboarding API - Client functions for onboarding endpoints
 *
 * Calls the Laravel backend at /api/v1/onboarding/*
 */
import axios from 'axios'

const API_BASE = '/api/v1/onboarding'

/**
 * Fetch all available onboarding items and completion status.
 * Returns projects, campaigns, clusters and onboardingCompleted flag.
 *
 * @returns {Promise<{projects: Array, campaigns: Array, clusters: Array, onboardingCompleted: boolean}>}
 */
export async function getOnboardingItems() {
    const response = await axios.get(`${API_BASE}/items`)
    return response.data
}

/**
 * Get the onboarding completion status for the current user.
 *
 * @returns {Promise<{onboardingCompleted: boolean}>}
 */
export async function getOnboardingStatus() {
    const response = await axios.get(`${API_BASE}/status`)
    return response.data
}

/**
 * Submit selected onboarding items.
 *
 * @param {{ projects: number[], campaigns: number[], clusters: number[] }} data
 * @returns {Promise<{message: string}>}
 */
export async function joinItems(data) {
    const response = await axios.post(`${API_BASE}/join`, data)
    return response.data
}
