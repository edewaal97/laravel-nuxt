<script setup>
const { $apiFetch } = useNuxtApp()
const { clearUser, fetchUser, isLoggedIn } = useAuth()
const toast = useToast()

onMounted(() => {
  fetchUser()
})

useHead({
  titleTemplate: (titleChunk) => {
    return titleChunk ? `${titleChunk} - Reddingsark Laravel Nuxt` : 'Reddingsark Laravel Nuxt'
  },
  meta: [
    { name: 'viewport', content: 'width=device-width, initial-scale=1' }
  ],
  link: [
    { rel: 'icon', href: '/favicon.ico' }
  ]
})

async function logout() {
  try {
    await $apiFetch('/logout', { method: 'POST' })

    toast.add({
      title: 'Succes',
      description: 'Succesvol uitgelogd',
      color: 'success'
    })
  } catch (e) {
    console.error(e)
  } finally {
    clearUser()
    await navigateTo('/')
  }
}
</script>

<template>
  <NuxtLoadingIndicator />
  <UApp>
    <UHeader :toggle="false">
      <template #left>
        <NuxtLink to="/">
          <AppLogo class="w-auto h-6 shrink-0" />
        </NuxtLink>
      </template>

      <template #right>
        <UButton
          v-if="!isLoggedIn"
          to="/login"
          variant="ghost"
          color="neutral"
        >
          Login
        </UButton>
        <UButton
          v-if="isLoggedIn"
          to="/profile"
          variant="ghost"
          color="neutral"
        >
          Profile
        </UButton>

        <UButton
          v-if="isLoggedIn"
          variant="ghost"
          color="neutral"
          @click="logout()"
        >
          Uitloggen
        </UButton>

        <UColorModeButton />
      </template>
    </UHeader>

    <UMain>
      <NuxtPage />
    </UMain>

    <USeparator icon="i-simple-icons-nuxtdotjs" />

    <UFooter>
      <template #left>
        <p class="text-sm text-muted">
          Built with Nuxt UI • © {{ new Date().getFullYear() }}
        </p>
      </template>

      <template #right />
    </UFooter>
  </UApp>
</template>
