<script setup lang="ts">
const config = useRuntimeConfig()
const route = useRoute()
const { data:article, pending, error } = await useFetch(`${config.public.apiBase}/api/articles/${route.params.slug}`, {
  transform: (response) => response.data
})
</script>

<template>
  <div class="container">
    <div v-if="pending">Loading article...</div>
    <div v-else-if="error">
      <p>Error loading data: {{ error.message }}</p>
    </div>

    <div v-else>
      <h1 class="text-xl font-bold">{{ article.title }}</h1>
      <img :src="`${article.banner}`" :alt="`banner image for ${article.title}`">
      <Editor v-model="article.content" />
    </div>
  </div>
</template>

<style scoped>

</style>
