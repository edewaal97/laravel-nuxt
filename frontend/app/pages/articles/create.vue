<script setup lang="ts">
const form = reactive({
  title: '',
  body: '',
})

const isLoading = ref(false);
const errors = ref([]);

const getError = (path: string) => errors.value?.[path]?.[0]

async function createArticle() {
  const config = useRuntimeConfig()
  const router = useRouter()

  isLoading.value = true
  errors.value = []

  try {
    await $fetch(`${config.public.apiBase}/api/articles`, {
      method: 'POST',
      body: form,
    })

    form.title = ''
    form.body = ''

    await router.push({ path: '/articles' })
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

      <UButton type="submit">Maak artikel</UButton>
    </UForm>
  </UContainer>
</template>

<style scoped>

</style>
