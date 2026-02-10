<script setup lang="ts">
const { $apiFetch } = useNuxtApp()
const route = useRoute()

const {
  data: article,
  status,
  error
} = await useFetch(`/api/articles/${route.params.slug}`, {
  $fetch: $apiFetch,
  transform: (response) => response.data
})
</script>

<template>
    <div>
      <div v-if="status === 'pending'">Loading article...</div>
      <div v-else-if="status === 'error'">
        <p>Error loading data: {{ error.message }}</p>
      </div>

      <div v-else>
        <UContainer>
          <UPageSection :title="`${article.title}`">
            <img
              class="mx-auto"
              :src="`${article.banner_image}`"
              :alt="`banner image for ${article.title}`"
            >
            <div v-html="article.body"></div>
          </UPageSection>
        </UContainer>
      </div>
    </div>
</template>

<style scoped>

</style>
