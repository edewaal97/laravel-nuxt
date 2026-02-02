<script setup lang="ts">
const config = useRuntimeConfig()
const route = useRoute()
const { data:article, pending, error } = await useFetch(`${config.public.apiBase}/api/articles/${route.params.slug}`, {
  transform: (response) => response.data
})
</script>

<template>
    <div>
      <div v-if="pending">Loading article...</div>
      <div v-else-if="error">
        <p>Error loading data: {{ error.message }}</p>
      </div>

      <div v-else>
        <UContainer>
          <UPageSection :title="`${article.title}`">
            <img class="mx-auto" :src="`${article.banner}`" :alt="`banner image for ${article.title}`">
            <USeparator icon="i-simple-icons-nuxtdotjs" />
            <div>{{ article.content }}</div>
          </UPageSection>
        </UContainer>
      </div>
    </div>
</template>

<style scoped>

</style>
