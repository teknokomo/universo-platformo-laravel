# start-frt — Start Pages Frontend Package

Frontend Vue.js package for Universo Platformo start pages. Provides the guest landing page, authenticated dashboard, and the authentication form.

## Overview

This package handles:

- **GuestStartPage** — public landing page for non-authenticated users (hero, testimonials, footer)
- **AuthenticatedStartPage** — onboarding wizard for authenticated users
- **AuthPage** — login and registration form
- **Auth state management** — reactive Supabase authentication state via Laravel backend
- **i18n** — simple translation composable using server-injected strings

## Package Structure

```
start-frt/
├── base/
│   └── resources/
│       └── js/
│           ├── App.vue                  # Root component (routing + auth provider)
│           ├── Pages/
│           │   ├── StartPage.vue        # Conditional page (guest vs authenticated)
│           │   ├── GuestStartPage.vue   # Landing page for non-authenticated users
│           │   ├── AuthenticatedStartPage.vue  # Onboarding for authenticated users
│           │   └── AuthPage.vue         # Login / registration form
│           ├── Components/
│           │   ├── AppAppBar.vue        # Navigation bar
│           │   ├── Hero.vue             # Hero section with CTA
│           │   ├── Testimonials.vue     # 4-card testimonials section
│           │   ├── StartFooter.vue      # Footer with links
│           │   └── OnboardingWizard.vue # Multi-step onboarding wizard
│           ├── Composables/
│           │   ├── useAuth.js           # Authentication state and actions
│           │   └── useI18n.js           # Translation composable
│           └── api/
│               └── onboarding.js        # Onboarding API client
├── README.md
└── README-RU.md
```

## Pages

### GuestStartPage

Public landing page shown to non-authenticated visitors. Contains:
- Full-screen background with gradient overlay
- Navigation bar with "Sign In" button
- Hero section with heading, description, and CTA button
- Testimonials section (4 cards in a row)
- Footer with copyright and links

### AuthenticatedStartPage

Shown to authenticated users who have not yet completed onboarding. Contains:
- Multi-step onboarding wizard (Welcome → Projects → Campaigns → Clusters → Finish)
- Completion screen shown if onboarding has already been done

### AuthPage

Login and registration form. Features:
- Tab switcher between Sign In and Sign Up
- Email + password fields with validation
- Error and success messages
- Back-to-home link

## Authentication

Authentication state is managed by `useAuth.js` and provided at the app root via Vue's `provide/inject` mechanism.

```js
// App.vue provides the auth store
provide('auth', authStore)

// Any component can inject it
const auth = inject('auth')
// auth.isAuthenticated, auth.loading, auth.user (all reactive)
```

Auth actions (`login`, `register`, `logout`) call the Laravel backend at `/api/v1/auth/*` which proxies to Supabase.

## i18n

Translations are injected by the Blade template as `window.__translations` and accessed via the `useI18n` composable:

```js
const { t } = useI18n()
t('hero.button') // → "To the future"
```

Translation strings are defined in `resources/lang/en/landing.php`.

## Integration

The package is mounted in `resources/js/app.js`:

```js
import App from '../../packages/start-frt/base/resources/js/App.vue'
const app = createApp(App)
app.mount('#app')
```
