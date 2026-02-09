<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()

const form = reactive({
  title: '',
  body: '',
})

const isLoading = ref(false)
const errors = ref<Record<string, string[]>>({})

const getError = (path: string) => errors.value?.[path]?.[0]

const { data, pending } = await useAsyncData(`article-${route.params.slug}`, () =>
  $fetch(`${config.public.apiBase}/api/articles/${route.params.slug}`)
)

if (data.value) {
  Object.assign(form, {
    title: data.value.data.title,
    body: data.value.data.body,
  })
}

async function updateArticle() {
  isLoading.value = true
  errors.value = {}

  try {
    await $fetch(`${config.public.apiBase}/api/articles/${route.params.slug}`, {
      method: 'PUT',
      body: form,
    })

    await router.push({ path: '/articles' })
  } catch (e: any) {
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
    <div v-if="pending" class="py-10 text-center">
      <UIcon name="i-heroicons-arrow-path" class="animate-spin h-8 w-8 text-gray-400" />
    </div>

    <div class="div" v-else>
      <UForm :state="form" @submit="updateArticle" class="space-y-4">
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

        <pre>{{ form.body }}</pre>

        <UButton type="submit" :loading="isLoading">
          Update artikel
        </UButton>
      </UForm>
    </div>
  </UContainer>
</template>
