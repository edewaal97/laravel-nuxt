export default defineNuxtPlugin({
  name: 'api-fetch',
  setup(nuxtApp) {
    const config = useRuntimeConfig()
    const apiFetch = $fetch.create({
      baseURL: config.public.apiBase,
    })
    return {
      provide: { apiFetch }
    }
  }
})
