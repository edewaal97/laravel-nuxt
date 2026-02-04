<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import {UButton} from "#components";

type Article = {
  id: string
  title: string
  slug: string
}

const config = useRuntimeConfig()
const { data:articles, pending, error } = await useFetch<Article[]>(`${config.public.apiBase}/api/articles`, {
  transform: (response) => response.data
})

const columns: TableColumn<Article>[] = [
  {
    accessorKey: 'id',
    header: '#',
  },
  {
    accessorKey: 'title',
    header: 'Titel',
  },
  {
    id: 'actions',
    header: 'Acties',
  }
]
</script>

<template>
  <div class="container">
    <div v-if="pending">Loading posts...</div>
    <div v-else-if="error">
      <p>Error loading data: {{ error.message }}</p>
    </div>

    <div v-else>
      <UContainer>
        <UPageSection title="Article List">
          <UTable :data="articles" :columns="columns" class="flex-1">

            <template #actions-cell="{ row: { original: article } }">
              <div class="flex gap-2">
                <UButton
                  icon="lucide:pencil"
                  :to="`/articles/${article.slug}/edit`"
                />
                <UButton
                  icon="lucide:trash-2"
                  color="error"
                />
              </div>
            </template>

            <template #title-cell="{ row: { original: article } }">
              <ULink :to="`/articles/${article.slug}`">{{ article.title }}</ULink>
            </template>

          </UTable>
        </UPageSection>
      </UContainer>
      <pre>{{ articles }}</pre>
      <uButton to="/articles/create" color="primary">Artikel Toevoegen</uButton>
    </div>
  </div>
</template>

<style scoped>

</style>
