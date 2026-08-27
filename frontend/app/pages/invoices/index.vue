<script setup lang="ts">
import type { TableColumn, TableRow } from '@nuxt/ui'

const baseURL: string = getApiUrl()
const query = reactive<Record<string, string>>({
  limit: "20",
  offset: "0",
})
const key = computed<string>(() =>  {
  const params = new URLSearchParams(query)
  params.sort()
  return `invoices:${params.toString()}`
})
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
</script>

<template>
  <div class="flex flex-col flex-1 w-full">
    <UTable
      :data="data?.data"
      :columns
      :loading="status === 'pending'"
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
  </div>
</template>
