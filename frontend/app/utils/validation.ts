import { z } from 'zod'

export const invoiceSchema = z.object({
  number: z.string().min(1, 'Номер обов\'язковий'),
  supplierName: z.string().min(1, 'Назва постачальника обов\'язкова'),
  supplierTaxId: z.string().min(1, 'ІПН постачальника обов\'язковий'),
  netAmount: z.coerce.number().positive('Сума має бути більшою за 0'),
  vatAmount: z.coerce.number().nonnegative('ПДВ не може бути від\'ємним'),
  grossAmount: z.coerce.number().positive('Загальна сума має бути більшою за 0'),
  currency: z.string().length(3, 'Код валюти має складатися з 3 символів'),
  issueDate: z.string().min(1, 'Дата виписки обов\'язкова'),
  dueDate: z.string().min(1, 'Термін оплати обов\'язковий'),
})

export const createInvoiceSchema = z.object({
  number: z.string().min(1, 'Номер обовʼязковий'),
  supplierName: z.string().min(1, 'Назва постачальника обовʼязкова'),
  supplierTaxId: z.string().min(1, 'ІПН постачальника обовʻязковий'),
  netAmount: z.coerce.number({ message: 'Вкажіть суму' }).positive('Сума має бути більшою за 0').transform((val) => val.toFixed(2)),
  vatAmount: z.coerce.number({ message: 'Вкажіть ПДВ' }).nonnegative('ПДВ не може бути відʼємним').transform((val) => val.toFixed(2)),
  currency: z.string().length(3, 'Код валюти має складатися з 3 символів').toUpperCase(),
  issueDate: z.string().min(1, 'Дата виписки обовʻязкова'),
  dueDate: z.string().min(1, 'Термін оплати обовʻязковий'),
  status: z.string().default('pending'),
}).refine(d => new Date(d.dueDate) >= new Date(d.issueDate), {
  path: ['dueDate'],
  message: 'Термін оплати не може бути раніше дати виписки'
})
export type InvoiceFormValues = z.infer<typeof invoiceSchema>
