<script setup lang="ts">
const config = useRuntimeConfig()
const { data:articles, pending, error } = await useFetch(`${config.public.apiBase}/api/articles`, {
  transform: (response) => response.data
})
</script>

<template>
  <div class="container">
    <h1 class="text-xl font-bold">Article List</h1>
    <div v-if="pending">Loading posts...</div>
    <div v-else-if="error">
      <p>Error loading data: {{ error.message }}</p>
    </div>

    <ul v-else class="article-list">
      <li v-for="article in articles" :key="article.id" class="article-item">
        <nuxt-link :to="`/articles/${article.slug}`">{{ article.title }}</nuxt-link>
      </li>
    </ul>
  </div>
</template>

<style scoped>

</style>
