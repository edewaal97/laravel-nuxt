<script setup lang="ts">
const { $apiFetch } = useNuxtApp()
const route = useRoute()
const toast = useToast()

const form = reactive({
  title: '',
  body: ''
})

const isLoading = ref(false)
const errors = ref<Record<string, string[]>>({})

const getError = (path: string) => errors.value?.[path]?.[0]

const { data, status, error } = await useAsyncData(
  `article-${route.params.slug}`,
  () => $apiFetch(`/api/articles/${route.params.slug}`)
)

if (error.value) {
  const statusCode = error.value.statusCode || error.value.response?.status

  if (statusCode === 404) {
    toast.add({
      title: '404 not found',
      description: `Dit artikel (${route.params.slug}) kan niet gevonden worden.`,
      color: 'success'
    })
    await navigateTo('/articles')
  }
}

const title = ref('Edit Article')

if (data.value) {
  Object.assign(form, {
    title: data.value.data.title,
    body: data.value.data.body
  })
  title.value = `'Edit Article ${data.value.data.title}'`
}

useSeoMeta({
  title
})

async function updateArticle() {
  isLoading.value = true
  errors.value = {}

  try {
    await $apiFetch(`/api/articles/${route.params.slug}`, {
      method: 'PUT',
      body: form
    })

    toast.add({
      title: 'Succes',
      description: 'Artikel opgeslagen',
      color: 'success'
    })

    await navigateTo('/articles')
  } catch (e: any) {
    const statusCode = e.response?.status

    if (statusCode === 403) {
      toast.add({
        title: 'Error 403 Forbidden',
        description: 'Je hebt geen rechten om dit arikel te bewerken',
        color: 'error'
      })
      return navigateTo('/articles')
    }

    if (e.response?._data?.errors) {
      errors.value = e.response._data.errors
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <UContainer>
    <div
      v-if="status === 'pending'"
      class="py-10 text-center"
    >
      <UIcon
        name="i-heroicons-arrow-path"
        class="animate-spin h-8 w-8 text-gray-400"
      />
    </div>

    <div
      v-if="status === 'error'"
      class="py-10 text-center"
    >
      <p>Error loading data: {{ error.message }}</p>
    </div>

    <div
      v-else
      class="div"
    >
      <UForm
        :state="form"
        class="space-y-4"
        @submit="updateArticle"
      >
        <UFormField
          label="Titel"
          :error="getError('title') || getError('slug')"
        >
          <UInput
            v-model="form.title"
            name="title"
          />
        </UFormField>

        <UFormField
          label="Body"
          :error="getError('body')"
        >
          <Editor
            id="body"
            v-model="form.body"
            name="body"
          />
        </UFormField>

        <pre>{{ form.body }}</pre>

        <UButton
          type="submit"
          :loading="isLoading"
        >
          Update artikel
        </UButton>
      </UForm>
    </div>
  </UContainer>
</template>
