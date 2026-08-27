<script setup lang="ts">
import { z } from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import { FetchError } from 'ofetch'

const toast = useToast()

const schema = createInvoiceSchema

type InvoiceFormValues = z.infer<typeof schema>

const state = reactive<Partial<InvoiceFormValues>>({
  number: '',
  supplierName: '',
  supplierTaxId: '',
  netAmount: undefined,
  vatAmount: undefined,
  currency: 'UAH',
  issueDate: '',
  dueDate: ''
})

const grossAmount = computed(() => {
  const net = Number(state.netAmount ?? 0)
  const vat = Number(state.vatAmount ?? 0)
  return (net + vat).toFixed(2)
})


const formRef = useTemplateRef('form')

async function onSubmit(event: FormSubmitEvent<InvoiceFormValues>) {
  console.log(event.data)
  try {
    const invoice = await $fetch<Invoice>('/api/invoices', {
      baseURL: getApiUrl(),
      method: 'POST',
      body: { ...event.data, grossAmount: grossAmount.value },
    })
    toast.add({ title: 'Інвойс створено', description: `Номер ${invoice.number}`, color: 'success' })
    await navigateTo({ name: 'invoices-id', params: { id: invoice.id } })
  } catch (error: unknown) {
    if (error instanceof FetchError) {
      if (error.statusCode === 422) {
        //validation error
        toast.add({ title: 'Помилка створення', description: error.data.detail, color: 'error' })
        console.debug(error.data)
        const formattedErrors = error.data.violations.map((field) => ({
          path: field.propertyPath,
          message: field.title,
        }))
        formRef.value?.setErrors(formattedErrors)

        return
      }
    }
    const err = error as { data?: { status?: number; message?: string } }
    toast.add({ title: 'Помилка створення', description: '...', color: 'error' })
  }
}
</script>

<template>
  <UContainer class="py-8">
    <UCard>
      <template #header>
        <h1 class="text-xl font-bold">Створення інвойсу</h1>
      </template>

      <UForm ref="form" @submit="onSubmit" :schema :state class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Номер інвойсу -->
          <UFormField label="Номер інвойсу" name="number" required>
            <UInput v-model="state.number" placeholder="INV-001" />
          </UFormField>

          <!-- Валюта -->
          <UFormField label="Валюта (ISO)" name="currency" required>
            <UInput v-model="state.currency" placeholder="UAH" maxlength="3" />
          </UFormField>

          <!-- Постачальник -->
          <UFormField label="Назва постачальника" name="supplierName" class="md:col-span-2" required>
            <UInput v-model="state.supplierName" placeholder="ТОВ 'Приклад'" />
          </UFormField>

          <!-- ІПН -->
          <UFormField label="ІПН постачальника" name="supplierTaxId" required>
            <UInput v-model="state.supplierTaxId" placeholder="1234567890" />
          </UFormField>

          <!-- Дати -->
          <UFormField label="Дата виписки" name="issueDate" required>
            <UInput v-model="state.issueDate" type="date" />
          </UFormField>

          <UFormField label="Термін оплати" name="dueDate" required>
            <UInput v-model="state.dueDate" type="date" />
          </UFormField>

          <!-- Суми -->
          <UFormField label="Сума без ПДВ" name="netAmount" required>
            <UInput v-model="state.netAmount" type="number" step="0.01" />
          </UFormField>

          <UFormField label="ПДВ" name="vatAmount" required>
            <UInput v-model="state.vatAmount" type="number" step="0.01" />
          </UFormField>

          <UFormField label="Загальна сума" name="grossAmount" class="md:col-span-2" required>
            <UInput v-model="state.grossAmount" type="number" step="0.01" />
          </UFormField>
        </div>

        <div class="flex justify-end">
          <UButton type="submit" color="primary">Створити інвойс</UButton>
        </div>
      </UForm>
    </UCard>
  </UContainer>
</template>
