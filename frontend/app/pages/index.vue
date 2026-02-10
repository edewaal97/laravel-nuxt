<script setup>

const { data, status, error } = await useAsyncData(
  'laravel-version',
  () => useNuxtApp().$apiFetch('/')
)

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
        title="Show me the articles!"
        description="Join thousands of developers building with Nuxt and Nuxt UI. Get this template and start shipping today."
        variant="subtle"
        :links="[{
          label: 'Show me the articles',
          to: '/articles',
          trailingIcon: 'i-lucide-arrow-right',
          color: 'neutral'
        }]"
      />
    </UPageSection>

    <UPageSection>
      <div v-if="status === 'pending'">Loading...</div>

      <div v-else-if="status === 'error'">
        <p>Error fetching data: {{ error.message }}</p>
      </div>

      <UPageCTA
        v-else
        :title="`Laravel Version: ${data.Laravel}`" />

    </UPageSection>
  </div>
</template>
