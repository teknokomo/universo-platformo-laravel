<template>
    <div class="hero">
        <div class="hero__container">
            <div class="hero__stack">
                <h1 class="hero__title">
                    {{ t('hero.titlePrefix') }}&nbsp;<span class="hero__title--highlight">{{ t('hero.titleHighlight') }}</span>
                </h1>
                <p class="hero__description">{{ t('hero.description') }}</p>
                <div class="hero__actions">
                    <a href="/auth" class="btn btn--info btn--large hero__cta">
                        {{ t('hero.button') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from '../Composables/useI18n.js'

const { t } = useI18n()
</script>

<style scoped>
.hero {
    width: 100%;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.hero__container {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    padding: 112px 16px 48px;
}

@media (min-width: 600px) {
    .hero__container {
        padding: 64px 24px 64px;
    }
}

.hero__stack {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 32px;
    width: 100%;
    max-width: 700px;
    text-align: center;
}

.hero__title {
    font-size: clamp(3rem, 10vw, 3.5rem);
    font-weight: 800;
    color: #fff;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
}

.hero__title--highlight {
    color: #90caf9;
}

.hero__description {
    color: #f0f0f0;
    font-size: 1.15rem;
    line-height: 1.6;
    margin: 0;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    max-width: 80%;
}

.hero__actions {
    padding-top: 16px;
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

.btn--info {
    background: #0288d1;
    color: #fff;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
}

.btn--info:hover {
    background: #0277bd;
}

.btn--large {
    padding: 12px 32px;
    font-size: 1rem;
    border-radius: 10px;
    min-width: max-content;
}
</style>
