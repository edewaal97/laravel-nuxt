<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const { verifyLoginLink } = useAuth()

const isLoading = ref(true)
const errorMessage = ref('')

onMounted(async ()=> {
  try {
    const result = await verifyLoginLink(route.params.id, route.query)

    if (result.success) {
      await navigateTo('/')
    } else {
      await navigateTo('/login')
    }
  } catch (e) {
    errorMessage.value = e.message
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen">
    <div v-if="isLoading" class="text-lg">Authenticating...</div>

    <div v-else-if="errorMessage" class="text-red-500 text-center">
      <p>{{ errorMessage }}</p>
    </div>
  </div>
</template>

