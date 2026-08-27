<script setup lang="ts">
interface Props {
  date: string
}
const props = defineProps<Props>()
</script>

<template>
  <time>{{ formatDate(Date.parse(date)) }}</time>
</template>