<template>
    <footer class="start-footer" :class="{ 'start-footer--internal': variant === 'internal' }">
        <div class="start-footer__container">
            <p class="start-footer__text">
                © {{ year }} Universo Platformo. {{ t('footer.rights') }}
            </p>
            <div class="start-footer__links">
                <a href="/legal/privacy" class="start-footer__link">{{ t('footer.privacy') }}</a>
                <a href="/legal/terms" class="start-footer__link">{{ t('footer.terms') }}</a>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from '../Composables/useI18n.js'

defineProps({
    variant: {
        type: String,
        default: 'public',
    },
})

const { t } = useI18n()
const year = computed(() => new Date().getFullYear())
</script>

<style scoped>
.start-footer {
    padding: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.start-footer--internal {
    border-top: 1px solid rgba(0, 0, 0, 0.08);
}

.start-footer__container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.start-footer__text {
    margin: 0;
    font-size: 0.8125rem;
    color: rgba(255, 255, 255, 0.6);
}

.start-footer--internal .start-footer__text {
    color: rgba(0, 0, 0, 0.5);
}

.start-footer__links {
    display: flex;
    gap: 16px;
}

.start-footer__link {
    font-size: 0.8125rem;
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    transition: color 0.2s;
}

.start-footer__link:hover {
    color: rgba(255, 255, 255, 0.9);
}

.start-footer--internal .start-footer__link {
    color: rgba(0, 0, 0, 0.5);
}

.start-footer--internal .start-footer__link:hover {
    color: rgba(0, 0, 0, 0.8);
}
</style>
