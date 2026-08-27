<script setup lang="ts">
interface Props {
  invoice: Invoice
  id: string
}

const { invoice } = defineProps<Props>();
const state = reactive({
  ...invoice
})

const schema = createInvoiceSchema
</script>

<template>
  <div class="max-w-3xl mx-auto p-6">
    <UCard>
      <template #header>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <div class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Рахунок</div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
              № {{ invoice.number }}
            </h1>
            <p class="text-xs text-gray-500 mt-0.5">ID: {{ invoice.id }}</p>
          </div>
          
          <InvoiceStatus :value="invoice.status" size="lg" />
        </div>
      </template>

      <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100 dark:border-gray-800 pb-6">
          <div class="space-y-2">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Постачальник</h3>
            <div class="text-base font-semibold text-gray-800 dark:text-gray-200">
              {{ invoice.supplierName }}
            </div>
            <div class="text-sm text-gray-500">
              ІПН / Tax ID: <span class="font-mono">{{ invoice.supplierTaxId }}</span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
              <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Дата виставлення</h3>
              <p class="text-sm text-gray-700 dark:text-gray-300"><InvoiceDate :date="invoice.issueDate" /></p>
            </div>
          </div>
        </div>
        <UForm :state="state" :schema class="gap-2 flex flex-col">
          <UFormField orientation="horizontal" label="Термін оплати" name="dueDate" required>
            <UInput v-model="state.dueDate" type="date" />
          </UFormField>

          <UFormField orientation="horizontal" label="Сума без ПДВ" name="netAmount" required>
            <UInput v-model="state.netAmount" type="number" step="0.01" />
          </UFormField>

          <UFormField orientation="horizontal" label="ПДВ" name="vatAmount" required>
            <UInput v-model="state.vatAmount" type="number" step="0.01" />
          </UFormField>
        </UForm>

        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 space-y-3">
          <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white pt-1">
            <span>Всього до сплати (Gross)</span>
            <span class="text-primary-600 dark:text-primary-400">
              {{ formatCurrency(invoice.grossAmount, invoice.currency) }}
            </span>
          </div>
        </div>
      </div>

      <template #footer>
        <div class="flex justify-between text-xs text-gray-400 italic">
          <span>Створено: <InvoiceDate :date="invoice.createdAt" /></span>
          <span>Оновлено: <InvoiceDate :date="invoice.updatedAt" /></span>
        </div>
      </template>
    </UCard>
  </div>
</template>
