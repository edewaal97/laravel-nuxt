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

if (error.value) {
  const statusCode = error.value.statusCode || error.value.response?.status

  if (statusCode === 404) {
    await navigateTo('/articles' )
  }
}

const title = ref(`${article.value.title}`)

useSeoMeta({
  title
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
              class="mx-auto rounded-lg shadow-lg"
              :src="`${article.banner_image}`"
              :alt="`banner image for ${article.title}`"
            >
            <div v-html="article.body"></div>
          </UPageSection>
        </UContainer>
      </div>
    </div>
</template>
