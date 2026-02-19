<script setup lang="ts">
const { $apiFetch } = useNuxtApp()
const toast = useToast()

const form = reactive({
  title: '',
  body: '',
  banner_image_upload: null
})

const isLoading = ref(false)
const errors = ref<Record<string, string[]>>({})

const getError = (path: string) => errors.value?.[path]?.[0]

const title = ref('Artikel toevoegen')

useSeoMeta({
  title
})

async function createArticle() {
  isLoading.value = true
  errors.value = {}

  const formData = new FormData()
  formData.append('title', form.title)
  formData.append('body', form.body)

  if (form.banner_image_upload) {
    formData.append('banner_image_upload', form.banner_image_upload)
  }

  try {
    await $apiFetch('/api/articles', {
      method: 'POST',
      body: formData
    })

    form.title = ''
    form.body = ''

    toast.add({
      title: 'Succes',
      description: 'Artikel toegevoegd',
      color: 'success'
    })

    await navigateTo('/articles')
  } catch (e: any) {
    const statusCode = e.response?.status

    if (statusCode === 403) {
      toast.add({
        title: 'Error 403 Forbidden',
        description: 'Je hebt geen rechten om een artikel toe te voegen',
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
    <UForm
      :state="form"
      class="space-y-4"
      @submit="createArticle"
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

      <UFormField
        label="Bannerafbeelding"
        :error="getError('banner_image_upload')"
      >
        <UFileUpload
          icon="i-lucide-image"
          label="Drop your image here"
          description="SVG, PNG, JPG or GIF (max. 2MB)"
          class="max-w-96 min-h-48"
          v-model="form.banner_image_upload"
        />
      </UFormField>

      <UButton
        type="submit"
        :loading="isLoading"
      >
        Maak artikel
      </UButton>
    </UForm>
  </UContainer>
</template>
