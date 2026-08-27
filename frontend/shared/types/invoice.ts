export type InvoiceStatus = 'pending' | 'approved' | 'rejected'

export interface Invoice {
  id: string
  number: string
  supplierName: string
  supplierTaxId: string
  netAmount: string
  vatAmount: string
  grossAmount: string
  status: InvoiceStatus
  currency: string
  issueDate: string
  dueDate: string
  createdAt: string
  updatedAt: string
}