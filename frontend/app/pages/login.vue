<script setup lang="ts">
definePageMeta({
  middleware: ['guest']
})

const { $apiFetch } = useNuxtApp()
const router = useRouter()
const toast = useToast()

const form = reactive({
  email: '',
  password: '',
})

const isLoading = ref(false);
const errors = ref<Record<string, string[]>>({});
const title = ref('Login')

useSeoMeta({
  title
})

const getError = (path: string) => errors.value?.[path]?.[0]

function csrf() {
  return $apiFetch('/sanctum/csrf-cookie')
}

async function login() {
  isLoading.value = true

  await csrf()

  try {
    await $apiFetch('/login', {
      method: 'POST',
      body: form,
    })

    const user = await $apiFetch('/api/user')
    const { setUser } = useAuth()
    setUser(user)

    toast.add({
      title: 'Succes',
      description: 'Succesvol ingelogd',
      color: 'success'
    })

    await router.replace({ path: '/' })
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
    <UForm :state="form" @submit="login" class="space-y-4">
      <UFormField label="E-mailadres" :error="getError('email')">
        <UInput
          v-model="form.email"
          name="email"
        />
      </UFormField>

      <UFormField label="password" :error="getError('password')">
        <UInput
          v-model="form.password"
          name="password"
          type="password"
        />
      </UFormField>

      <UButton type="submit">Login</UButton>

    </UForm>



  </UContainer>
</template>

<style scoped>

</style>
