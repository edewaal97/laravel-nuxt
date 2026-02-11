<script setup lang="ts">
const { $apiFetch } = useNuxtApp()
const toast = useToast()

const form = reactive({
  title: '',
  body: '',
})

const isLoading = ref(false);
const errors = ref<Record<string, string[]>>({})

const getError = (path: string) => errors.value?.[path]?.[0]

const title = ref('Artikel toevoegen')

useSeoMeta({
  title
})

async function createArticle() {
  isLoading.value = true
  errors.value = {}

  try {
    await $apiFetch('/api/articles', {
      method: 'POST',
      body: form,
    })

    form.title = ''
    form.body = ''

    toast.add({
      title: 'Succes',
      description: 'Artikel toegevoegd',
      color: 'success'
    })

    await navigateTo('/articles' )
  } catch (e: any) {
    if (e.response?._data?.errors) {
      errors.value = e.response._data.errors;
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <UContainer>
    <UForm :state="form" @submit="createArticle" class="space-y-4">
      <UFormField label="Titel" :error="getError('title') || getError('slug')">
        <UInput
          v-model="form.title"
          name="title"
        />
      </UFormField>

      <UFormField label="Body" :error="getError('body')">
        <Editor
          v-model="form.body"
          name="body"
          id="body"
        />
      </UFormField>

      <UButton
        type="submit"
        :loading="isLoading"
      >Maak artikel</UButton>
    </UForm>
  </UContainer>
</template>

<style scoped>

</style>
