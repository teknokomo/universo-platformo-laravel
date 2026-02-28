<template>
    <div class="testimonials">
        <div class="testimonials__grid">
            <div
                v-for="item in items"
                :key="item.id"
                class="testimonials__card"
            >
                <div class="testimonials__card-content">
                    <p class="testimonials__quote">{{ item.quote }}</p>
                    <div class="testimonials__author">
                        <span class="testimonials__avatar">{{ item.name[0] }}</span>
                        <div>
                            <p class="testimonials__name">{{ item.name }}</p>
                            <p class="testimonials__role">{{ item.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from '../Composables/useI18n.js'

const { t } = useI18n()

const items = [
    {
        id: 1,
        quote: t('testimonials.item1.quote'),
        name: t('testimonials.item1.name'),
        role: t('testimonials.item1.role'),
    },
    {
        id: 2,
        quote: t('testimonials.item2.quote'),
        name: t('testimonials.item2.name'),
        role: t('testimonials.item2.role'),
    },
    {
        id: 3,
        quote: t('testimonials.item3.quote'),
        name: t('testimonials.item3.name'),
        role: t('testimonials.item3.role'),
    },
    {
        id: 4,
        quote: t('testimonials.item4.quote'),
        name: t('testimonials.item4.name'),
        role: t('testimonials.item4.role'),
    },
]
</script>

<style scoped>
.testimonials {
    padding: 24px 16px;
}

.testimonials__grid {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
}

@media (min-width: 900px) {
    .testimonials__grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.testimonials__card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    overflow: hidden;
}

.testimonials__card-content {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.testimonials__quote {
    color: #e0e0e0;
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0;
    font-style: italic;
}

.testimonials__author {
    display: flex;
    align-items: center;
    gap: 10px;
}

.testimonials__avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #1976d2;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.testimonials__name {
    margin: 0;
    font-weight: 600;
    font-size: 0.875rem;
    color: #fff;
}

.testimonials__role {
    margin: 0;
    font-size: 0.75rem;
    color: #bbb;
}
</style>
