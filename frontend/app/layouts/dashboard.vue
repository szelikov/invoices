<script setup lang="ts">
const title = ref<string>('BIP')
</script>

<template>
  <div>
    <UHeader :title>
      <NuxtLink :to="{ name: 'invoices' }">Invoices</NuxtLink>
    </UHeader>
    <UMain>
      <slot />
    </UMain>
    <UFooter />
  </div>
</template>
