<script setup>
const config = useRuntimeConfig()
const { data, pending, error } = await useFetch(`${config.public.apiBase}/`, {
  key: 'laravel-version'
})
</script>

<template>
  <div>
    <UPageHero
      title="Home"
      description="A production-ready starter template powered by Nuxt UI. Build beautiful, accessible, and performant applications in minutes, not hours."
      :links="[{
        label: 'Get started',
        to: '/about',
        trailingIcon: 'i-lucide-arrow-right',
        size: 'xl'
      }]"
    />

    <UPageSection>
      <UPageCTA
        title="Ready to build your next Nuxt app?"
        description="Join thousands of developers building with Nuxt and Nuxt UI. Get this template and start shipping today."
        variant="subtle"
        :links="[{
          label: 'Start building',
          to: 'https://ui.nuxt.com/docs/getting-started/installation/nuxt',
          target: '_blank',
          trailingIcon: 'i-lucide-arrow-right',
          color: 'neutral'
        }, {
          label: 'View on GitHub',
          to: 'https://github.com/nuxt-ui-templates/starter',
          target: '_blank',
          icon: 'i-simple-icons-github',
          color: 'neutral',
          variant: 'outline'
        }]"
      />
    </UPageSection>

    <UPageSection>
      <div v-if="pending">Loading...</div>

      <div v-else-if="error">
        <p>Error fetching data: {{ error.message }}</p>
      </div>

      <UPageCTA
        v-else
        :title="`Laravel Version: ${data.Laravel}`" />

    </UPageSection>
  </div>
</template>
