<template>
    <div class="guest-start-page">
        <!-- Background gradient overlay -->
        <div class="guest-start-page__overlay" aria-hidden="true"></div>

        <!-- Navigation bar -->
        <AppAppBar />

        <!-- Hero section (grows to fill space, content centered) -->
        <div class="guest-start-page__hero">
            <Hero />
        </div>

        <!-- Testimonials fixed to bottom -->
        <div class="guest-start-page__testimonials">
            <Testimonials />
        </div>

        <!-- Footer -->
        <div class="guest-start-page__footer">
            <StartFooter />
        </div>
    </div>
</template>

<script setup>
import AppAppBar from '../Components/AppAppBar.vue'
import Hero from '../Components/Hero.vue'
import Testimonials from '../Components/Testimonials.vue'
import StartFooter from '../Components/StartFooter.vue'
</script>

<style scoped>
.guest-start-page {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    position: relative;
    background-image: url('/images/background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* Gradient overlay for better text readability */
.guest-start-page__overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(100, 160, 220, 0.6), transparent);
    pointer-events: none;
    z-index: 0;
}

/* Fallback background when image is not available */
.guest-start-page::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0d47a1 0%, #1565c0 40%, #0a2744 100%);
    z-index: -1;
}

.guest-start-page__hero {
    flex: 1;
    display: flex;
    flex-direction: column;
    z-index: 1;
}

.guest-start-page__testimonials {
    flex-shrink: 0;
    z-index: 1;
}

.guest-start-page__footer {
    flex-shrink: 0;
    z-index: 1;
}
</style>
