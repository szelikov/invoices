// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  modules: ['@nuxt/ui'],
  devtools: { enabled: true },
  runtimeConfig: {
    apiServerUrl: 'http://nginx/api',
    public: {
      apiServerUrl: '/api'
    }
  },
  icon: {
    customFetchPath: '/_nuxt_icon' 
  }
})
