// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({

  modules: [
    '@nuxt/eslint',
    '@nuxt/ui'
  ],

  devtools: {
    enabled: true
  },
  app: {
    head: {
      title: 'Reddingsark Laravel Nuxt',
      htmlAttrs: {
        lang: 'nl'
      }
    }
  },

  css: ['~/assets/css/main.css'],

  runtimeConfig: {
    public: {
      apiBase: '', // Default value, overridden by NUXT_PUBLIC_API_BASE
      frontendUrl: '' // Default value, overridden by NUXT_PUBLIC_FRONTEND_URL
    }
  },

  routeRules: {
    '/': { prerender: true }
  },

  compatibilityDate: '2025-01-15',

  vite: {
    server: {
      allowedHosts: process.env.NUXT_ALLOWED_HOST
        ? [process.env.NUXT_ALLOWED_HOST]
        : []
    }
  },

  eslint: {
    config: {
      stylistic: {
        commaDangle: 'never',
        braceStyle: '1tbs'
      }
    }
  }
})
