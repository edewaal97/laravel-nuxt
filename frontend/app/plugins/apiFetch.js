export default defineNuxtPlugin({
  name: 'api-fetch',
  setup(nuxtApp) {
    const config = useRuntimeConfig()
    const apiFetch = $fetch.create({
      baseURL: config.public.apiBase,
      credentials: 'include',
      async onRequest({ options }) {
        const xsrfToken = useCookie('XSRF-TOKEN').value

        options.headers = {
          ...options.headers,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }

        if (xsrfToken) {
          options.headers['X-XSRF-TOKEN'] = xsrfToken
        }
      }
    })
    return {
      provide: { apiFetch }
    }
  }
})
