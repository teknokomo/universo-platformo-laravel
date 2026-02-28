/**
 * useI18n - Simple i18n composable for start-frt package
 *
 * Reads translations from window.__translations (injected by Blade template)
 * and provides a t() function for key-based string lookup.
 */

const translations = window.__translations || {}

/**
 * Get a nested translation value by dot-separated key.
 * Falls back to the key itself if not found.
 */
function get(obj, path, fallback) {
    const parts = path.split('.')
    let current = obj
    for (const part of parts) {
        if (current === null || current === undefined || typeof current !== 'object') return fallback
        current = current[part]
    }
    return current !== null && current !== undefined ? current : fallback
}

export function useI18n() {
    function t(key) {
        return get(translations, key, key)
    }

    return { t }
}
