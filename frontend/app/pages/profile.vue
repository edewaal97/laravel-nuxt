<script setup lang="ts">
const user = ref(null)

const { $apiFetch } = useNuxtApp()

onMounted(async () => {
  const response = await $apiFetch('/api/user')
  user.value = response
})

</script>

<template>
  <UContainer>
    <pre>
      Name: {{user?.name}}
      E-mailadres: {{ user?.email }}
    </pre>
  </UContainer>
</template>

<style scoped>

</style>
