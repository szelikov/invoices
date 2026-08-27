import { z } from 'zod'

// Базовые типы для валидации чисел
const financeFields = {
  netAmount: z.number().gt(0, 'Має бути більше 0'),
  vatAmount: z.number().gte(0, 'Не може бути менше 0'),
  grossAmount: z.number()
}

export const invoiceEditSchema = z.object({
  ...financeFields,
  due_date: z.string().min(1)
})

export const invoiceCreateSchema = z.object({
  ...financeFields,
  due_date: z.string().min(1),
  number: z.string().min(1, 'Обовʼязково'),
  issue_date: z.string().min(1, 'Обовʼязково'),
}).refine(data => new Date(data.due_date) >= new Date(data.issue_date), {
  path: ['due_date'],
  message: 'Ошибка дат'
})
