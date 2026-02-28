<template>
    <div class="auth-start-page">
        <!-- Loading spinner -->
        <div v-if="!isReady" class="auth-start-page__loader" aria-label="Loading">
            <div class="spinner"></div>
        </div>

        <!-- Completion screen if already onboarded -->
        <template v-else-if="onboardingCompleted">
            <div class="auth-start-page__content">
                <div class="auth-start-page__container">
                    <div class="completion-step">
                        <h2 class="completion-step__title">{{ t('onboarding.completedTitle') }}</h2>
                        <p class="completion-step__description">{{ t('onboarding.completedDescription') }}</p>
                        <button class="btn btn--primary" @click="onboardingCompleted = false">
                            {{ t('onboarding.startOver') }}
                        </button>
                    </div>
                </div>
            </div>
            <StartFooter variant="internal" />
        </template>

        <!-- Onboarding wizard for new users -->
        <template v-else>
            <div class="auth-start-page__content">
                <OnboardingWizard @complete="onboardingCompleted = true" />
            </div>
            <StartFooter variant="internal" />
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import OnboardingWizard from '../Components/OnboardingWizard.vue'
import StartFooter from '../Components/StartFooter.vue'
import { useI18n } from '../Composables/useI18n.js'
import { getOnboardingStatus } from '../api/onboarding.js'

const { t } = useI18n()

const isReady = ref(false)
const onboardingCompleted = ref(null)

onMounted(async () => {
    try {
        const data = await getOnboardingStatus()
        onboardingCompleted.value = data.onboardingCompleted
    } catch (err) {
        console.error('[AuthenticatedStartPage] Failed to check onboarding status, defaulting to wizard:', err)
        onboardingCompleted.value = false
    }
    isReady.value = true
})
</script>

<style scoped>
.auth-start-page {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: #f5f5f5;
}

.auth-start-page__loader {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 50vh;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e0e0e0;
    border-top-color: #1976d2;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.auth-start-page__content {
    flex: 1;
}

.auth-start-page__container {
    max-width: 800px;
    margin: 0 auto;
    padding: 112px 16px 32px;
}

@media (min-width: 600px) {
    .auth-start-page__container {
        padding: 112px 24px 48px;
    }
}

.completion-step {
    text-align: center;
    padding: 40px 0;
}

.completion-step__title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #1a1a2e;
}

.completion-step__description {
    font-size: 1rem;
    color: #555;
    margin-bottom: 24px;
    line-height: 1.6;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: background 0.2s;
}

.btn--primary {
    background: #1976d2;
    color: #fff;
}

.btn--primary:hover {
    background: #1565c0;
}
</style>
