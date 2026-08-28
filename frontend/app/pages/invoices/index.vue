<script setup lang="ts">
import type { ButtonProps, TableColumn, TableRow } from '@nuxt/ui'
import { refDebounced } from '@vueuse/core'

const baseURL: string = getApiUrl()
const query = reactive({
  limit: "20",
  offset: "0",
})
const rawKey = computed<string>(() =>  {
  const params = new URLSearchParams(query)
  params.sort()
  return `invoices:${params.toString()}`
})
const key = refDebounced<string>(rawKey, 5)
const { data, status } = await useFetch<PageableResponse<Invoice>>('/invoices', {
  baseURL,
  key,
  query,
})

const onClickRow = (_event: Event, row: TableRow<Invoice>) => {
  navigateTo({ name: 'invoices-id', params: { id: row.original.id }})
}

const columns = reactive<TableColumn<Invoice>[]>([
  { accessorKey: 'number', header: 'Number' },
  { accessorKey: 'supplierName', header: 'Supplier' },
  { accessorKey: 'grossAmount', header: 'Amount' },
  { accessorKey: 'status', header: 'Status' },
  { accessorKey: 'dueDate', header: 'Due Date' },
])
const links = ref<ButtonProps[]>([
  {
    label: 'Додати Рахунок',
    icon: 'i-simple-icons-addthis',
    to: { name: 'invoices-create' },
  }
])
</script>

<template>
  <UPage>
    <UContainer>
    <UPageHeader title="Invoices" :links />
    <UPageBody>
      <UTable
        :data="data?.data"
        :columns
        :loading="status === 'pending' || status === 'idle'"
        class="flex-1"
        @select="onClickRow"
      >
        <template #grossAmount-cell="{ row }">
          {{ formatCurrency(row.original.grossAmount, row.original.currency) }}
        </template>
        <template #dueDate-cell="{ getValue }">
          <InvoiceDate :date="getValue<string>()" />
        </template>
        <template #status-cell="{ getValue }">
          <InvoiceStatus :value="getValue<InvoiceStatus>()" />
        </template>
      </UTable>
      <Pagination
        v-model:limit="query.limit"
        v-model:offset="query.offset"
        :meta="data?.meta"
        :disabled="status === 'pending' || status === 'idle'"
      />
    </UPageBody>
    </UContainer>
  </UPage>
</template>
