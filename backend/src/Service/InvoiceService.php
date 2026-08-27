<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Dto\UpdateInvoiceDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvoiceService
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    public function updateInvoice(Invoice $invoice, UpdateInvoiceDto $dto): Invoice
    {
        // 1. Проверка главного бизнес-правила: разрешено редактирование только в статусе pending
        if ($invoice->getStatus() !== 'pending') {
            throw new BadRequestHttpException('Only invoices with "pending" status can be updated.');
        }

        // 2. Маппинг данных из DTO в сущность Doctrine
        $invoice->setNetAmount($dto->netAmount);
        $invoice->setVatAmount($dto->vatAmount);
        $invoice->setGrossAmount($dto->grossAmount);
        $invoice->setDueDate($dto->dueDate);
        
        if ($dto->currency !== null) $invoice->setCurrency($dto->currency);
        if ($dto->supplierName !== null) $invoice->setSupplierName($dto->supplierName);
        if ($dto->supplierTaxId !== null) $invoice->setSupplierTaxId($dto->supplierTaxId);

        // 3. Сохранение изменений в БД
        $this->em->flush();

        return $invoice;
    }
}
