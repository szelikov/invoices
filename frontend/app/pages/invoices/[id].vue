<script setup lang="ts">
definePageMeta({
  // TODO: Use strict UUID validation
  validate: (route): boolean => typeof route.params.id === 'string' && route.params.id.length === 36,
  props: true
})

interface Props {
  id: string
}

const { id } = defineProps<Props>()
const baseURL: string = getApiUrl()

const { data: invoice } = await useFetch(`/invoices/${id}`, {
  key: () => `invoices-${id}`,
  baseURL,
})

</script>

<template>
  <NuxtPage :id :invoice />
</template>
