<template>
    <div class="onboarding-wizard">
        <div class="onboarding-wizard__container">
            <!-- Progress indicator -->
            <div class="onboarding-wizard__progress">
                <div
                    v-for="(step, index) in steps"
                    :key="step.id"
                    class="onboarding-wizard__step-indicator"
                    :class="{
                        'onboarding-wizard__step-indicator--active': index === currentStep,
                        'onboarding-wizard__step-indicator--done': index < currentStep,
                    }"
                >
                    <span class="onboarding-wizard__step-number">{{ index + 1 }}</span>
                    <span class="onboarding-wizard__step-label">{{ step.label }}</span>
                </div>
            </div>

            <!-- Step content -->
            <div class="onboarding-wizard__content">
                <!-- Welcome step (step 0) -->
                <div v-if="currentStep === 0" class="onboarding-wizard__welcome">
                    <h2 class="onboarding-wizard__title">{{ t('onboarding.welcomeTitle') }}</h2>
                    <p class="onboarding-wizard__subtitle">{{ t('onboarding.welcomeSubtitle') }}</p>
                    <button class="btn btn--primary" @click="currentStep = 1">
                        {{ t('onboarding.welcomeButton') }}
                    </button>
                </div>

                <!-- Selection steps (1, 2, 3) -->
                <div v-else-if="currentStep < steps.length - 1" class="onboarding-wizard__selection">
                    <h2 class="onboarding-wizard__title">{{ steps[currentStep].title }}</h2>
                    <p class="onboarding-wizard__subtitle">{{ steps[currentStep].description }}</p>

                    <div class="onboarding-wizard__loading" v-if="isLoading">
                        <div class="spinner"></div>
                    </div>

                    <div v-else class="onboarding-wizard__options">
                        <label
                            v-for="option in steps[currentStep].options"
                            :key="option.id"
                            class="onboarding-wizard__option"
                            :class="{ 'onboarding-wizard__option--selected': isSelected(currentStep, option.id) }"
                        >
                            <input
                                type="checkbox"
                                :value="option.id"
                                :checked="isSelected(currentStep, option.id)"
                                @change="toggleSelection(currentStep, option.id)"
                                class="sr-only"
                            />
                            <span class="onboarding-wizard__option-name">{{ option.name }}</span>
                            <span v-if="option.description" class="onboarding-wizard__option-desc">{{ option.description }}</span>
                        </label>
                    </div>

                    <div class="onboarding-wizard__actions">
                        <button class="btn btn--outline" @click="currentStep--">
                            {{ t('onboarding.back') }}
                        </button>
                        <button class="btn btn--primary" @click="currentStep++">
                            {{ t('onboarding.next') }}
                        </button>
                    </div>
                </div>

                <!-- Final step: Review & Submit -->
                <div v-else class="onboarding-wizard__final">
                    <h2 class="onboarding-wizard__title">{{ t('onboarding.reviewTitle') }}</h2>
                    <p class="onboarding-wizard__subtitle">{{ t('onboarding.reviewSubtitle') }}</p>

                    <p v-if="submitError" class="onboarding-wizard__error">{{ submitError }}</p>

                    <div class="onboarding-wizard__actions">
                        <button class="btn btn--outline" @click="currentStep--" :disabled="isSubmitting">
                            {{ t('onboarding.back') }}
                        </button>
                        <button class="btn btn--primary" @click="handleSubmit" :disabled="isSubmitting">
                            <span v-if="isSubmitting" class="btn__spinner"></span>
                            <span v-else>{{ t('onboarding.finish') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useI18n } from '../Composables/useI18n.js'
import { getOnboardingItems, joinItems } from '../api/onboarding.js'

const emit = defineEmits(['complete'])
const { t } = useI18n()

const currentStep = ref(0)
const isLoading = ref(false)
const isSubmitting = ref(false)
const submitError = ref('')
const selections = reactive({ 1: [], 2: [], 3: [] })

const steps = ref([
    { id: 'welcome', label: t('onboarding.stepWelcome') },
    {
        id: 'projects',
        label: t('onboarding.stepProjects'),
        title: t('onboarding.projectsTitle'),
        description: t('onboarding.projectsDescription'),
        options: [],
    },
    {
        id: 'campaigns',
        label: t('onboarding.stepCampaigns'),
        title: t('onboarding.campaignsTitle'),
        description: t('onboarding.campaignsDescription'),
        options: [],
    },
    {
        id: 'clusters',
        label: t('onboarding.stepClusters'),
        title: t('onboarding.clustersTitle'),
        description: t('onboarding.clustersDescription'),
        options: [],
    },
    { id: 'finish', label: t('onboarding.stepFinish') },
])

onMounted(async () => {
    isLoading.value = true
    try {
        const data = await getOnboardingItems()
        if (data.projects) steps.value[1].options = data.projects
        if (data.campaigns) steps.value[2].options = data.campaigns
        if (data.clusters) steps.value[3].options = data.clusters
    } catch (err) {
        console.error('[OnboardingWizard] Failed to load items:', err)
    } finally {
        isLoading.value = false
    }
})

function isSelected(stepIndex, id) {
    return selections[stepIndex]?.includes(id)
}

function toggleSelection(stepIndex, id) {
    if (!selections[stepIndex]) selections[stepIndex] = []
    const idx = selections[stepIndex].indexOf(id)
    if (idx === -1) {
        selections[stepIndex].push(id)
    } else {
        selections[stepIndex].splice(idx, 1)
    }
}

async function handleSubmit() {
    submitError.value = ''
    isSubmitting.value = true
    try {
        await joinItems({
            projects: selections[1],
            campaigns: selections[2],
            clusters: selections[3],
        })
        emit('complete')
    } catch (err) {
        submitError.value = t('onboarding.submitError')
        console.error('[OnboardingWizard] Submit failed:', err)
    } finally {
        isSubmitting.value = false
    }
}
</script>

<style scoped>
.onboarding-wizard {
    padding: 100px 16px 32px;
}

.onboarding-wizard__container {
    max-width: 720px;
    margin: 0 auto;
}

.onboarding-wizard__progress {
    display: flex;
    gap: 8px;
    margin-bottom: 40px;
    overflow-x: auto;
    padding-bottom: 4px;
}

.onboarding-wizard__step-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 24px;
    border: 1.5px solid #ddd;
    background: #fff;
    white-space: nowrap;
    opacity: 0.6;
}

.onboarding-wizard__step-indicator--active {
    border-color: #1976d2;
    background: #e3f2fd;
    opacity: 1;
}

.onboarding-wizard__step-indicator--done {
    border-color: #4caf50;
    background: #e8f5e9;
    opacity: 1;
}

.onboarding-wizard__step-number {
    font-weight: 700;
    font-size: 0.8125rem;
    color: #555;
}

.onboarding-wizard__step-label {
    font-size: 0.8125rem;
    color: #555;
}

.onboarding-wizard__content {
    background: #fff;
    border-radius: 16px;
    padding: 40px 32px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.onboarding-wizard__welcome,
.onboarding-wizard__selection,
.onboarding-wizard__final {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.onboarding-wizard__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.onboarding-wizard__subtitle {
    font-size: 1rem;
    color: #555;
    margin: 0;
    line-height: 1.6;
}

.onboarding-wizard__loading {
    display: flex;
    justify-content: center;
    padding: 24px;
}

.spinner {
    width: 32px;
    height: 32px;
    border: 3px solid #e0e0e0;
    border-top-color: #1976d2;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.onboarding-wizard__options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
}

.onboarding-wizard__option {
    padding: 14px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.onboarding-wizard__option:hover {
    border-color: #1976d2;
    background: #f0f7ff;
}

.onboarding-wizard__option--selected {
    border-color: #1976d2;
    background: #e3f2fd;
}

.onboarding-wizard__option-name {
    font-weight: 600;
    font-size: 0.9375rem;
    color: #1a1a2e;
}

.onboarding-wizard__option-desc {
    font-size: 0.8125rem;
    color: #666;
    line-height: 1.4;
}

.onboarding-wizard__actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 8px;
}

.onboarding-wizard__error {
    color: #d32f2f;
    font-size: 0.875rem;
    padding: 8px 12px;
    background: #fde8e8;
    border-radius: 6px;
    margin: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: background 0.2s, opacity 0.2s;
    border: none;
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

.btn--outline {
    background: transparent;
    color: #1976d2;
    border: 1.5px solid #1976d2;
}

.btn--outline:hover:not(:disabled) {
    background: #e3f2fd;
}

.btn__spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: inline-block;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
