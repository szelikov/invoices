<script setup lang="ts">
interface Props {
  value: InvoiceStatus
}

const props = defineProps<Props>()

const color = computed(() => {
  switch(props.value) {
    case 'approved':
      return 'success'
    case 'pending':
      return 'neutral'
    case 'rejected':
      return 'error'
      return 'warning'
      return 'primary'
      return 'secondary'
      return 'info'
    }
})
</script>

<template>
  <UBadge
    class="capitalize"
    variant="subtle"
    :color
  >
    {{ value }}
  </UBadge>
</template>

<style scoped></style>
