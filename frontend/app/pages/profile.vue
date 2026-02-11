<script setup lang="ts">
definePageMeta({
  middleware: ['auth']
})

const { user } = useAuth()

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
