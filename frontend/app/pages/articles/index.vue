<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import {UButton} from "#components";

type Article = {
  id: string
  title: string
  slug: string
}

const config = useRuntimeConfig()
const toast = useToast()

const {
  data:articles,
  pending,
  error,
  refresh
} = await useFetch<Article[]>(`${config.public.apiBase}/api/articles`, {
  transform: (response) => response.data
})

const deletingId = ref<string | null>(null)

async function onDelete(slug: string) {
  if (!confirm('Weet je zeker dat je dit artikel wilt verwijderen?')) return

  deletingId.value = slug
  try {
    await $fetch(`${config.public.apiBase}/api/articles/${slug}`, {
      method: 'DELETE'
    })

    toast.add({ title: 'Succes', description: 'Artikel verwijderd', color: 'success' })
    await refresh() // Refetch the list from Laravel
  } catch (err) {
    toast.add({ title: 'Fout', description: 'Kon artikel niet verwijderen', color: 'error' })
  } finally {
    deletingId.value = null
  }
}

const columns: TableColumn<Article>[] = [
  { accessorKey: 'id', header: '#', },
  { accessorKey: 'title', header: 'Titel', },
  { id: 'actions', header: 'Acties', },
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
                  variant="ghost"
                  :to="`/articles/${article.slug}/edit`"
                />
                <UButton
                  icon="lucide:trash-2"
                  color="error"
                  variant="ghost"
                  :loading="deletingId === article.slug"
                  @click="onDelete(article.slug)"
                />
              </div>
            </template>

            <template #title-cell="{ row: { original: article } }">
              <ULink :to="`/articles/${article.slug}`">{{ article.title }}</ULink>
            </template>

          </UTable>
        </UPageSection>
        <uButton to="/articles/create" color="primary">Artikel Toevoegen</uButton>
      </UContainer>
    </div>
  </div>
</template>

<style scoped>

</style>
