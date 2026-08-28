<script setup lang="ts">
interface Props {
  id: string
  invoice: Invoice
}

defineProps<Props>()
</script>

<template>
  <UContainer>
    <UPage>
      <UPageHeader
        :title="`№ ${invoice.number}`"
        :description="`ID: ${invoice.id}`"
      >
        <template #links>
          <InvoiceStatus :value="invoice.status" size="lg" />
          <UButton
            :to="{ name: 'invoices-id-edit', params: { id } }"
            :disabled="invoice.status !== 'pending'"
            icon="i-heroicons-pencil-square"
            variant="ghost"
            label="Редагувати"
          />
        </template>
      </UPageHeader>
      <UPageBody>
      <UCard>
        <template #header>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Назва постачальника</h3>
              <div class="text-base font-semibold text-gray-800 dark:text-gray-200">
                {{ invoice.supplierName }}
              </div>
              <div class="text-sm text-gray-500">
                ІПН постачальника: <span class="font-mono">{{ invoice.supplierTaxId }}</span>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Дата виписки</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300"><InvoiceDate :date="invoice.issueDate" /></p>
              </div>
              <div class="space-y-1">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Термін оплати</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium text-amber-600 dark:text-amber-400">
                  <InvoiceDate :date="invoice.dueDate" />
                </p>
              </div>
            </div>
          </div>
        </template>

        <div>
          <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
            <span>Чиста сума (Net)</span>
            <span class="font-medium">{{ formatCurrency(invoice.netAmount, invoice.currency) }}</span>
          </div>
          
          <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800 pb-3">
            <span>ПДВ (VAT)</span>
            <span class="font-medium">{{ formatCurrency(invoice.vatAmount, invoice.currency) }}</span>
          </div>

          <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white pt-1">
            <span>Всього до сплати (Gross)</span>
            <span class="text-primary-600 dark:text-primary-400">
              {{ formatCurrency(invoice.grossAmount, invoice.currency) }}
            </span>
          </div>
        </div>

        <template #footer>
          <div class="flex justify-between text-xs text-gray-400 italic">
            <span>Створено: <InvoiceDate :date="invoice.createdAt" /></span>
            <span>Оновлено: <InvoiceDate :date="invoice.updatedAt" /></span>
          </div>
        </template>
      </UCard>
      </UPageBody>
    </UPage>
  </UContainer>
</template>
