<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UpdateInvoiceDto
{
    #[Assert\NotBlank]
    #[Assert\Positive(message: 'Net amount must be greater than 0.')]
    public ?string $netAmount = null;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero(message: 'VAT amount must be greater than or equal to 0.')]
    public ?string $vatAmount = null;

    #[Assert\NotBlank]
    public ?string $grossAmount = null;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['pending', 'approved', 'rejected'], message: 'Invalid status.')]
    public ?string $status = null;

    #[Assert\NotBlank]
    public ?\DateTime $issueDate = null;

    #[Assert\NotBlank]
    public ?\DateTime $dueDate = null;

    public ?string $currency = null;
    public ?string $supplierName = null;
    public ?string $supplierTaxId = null;

    // Кросс-полевая валидация дат и математики переехала в DTO
    #[Assert\Callback]
    public function validateBusinessRules(ExecutionContextInterface $context): void
    {
        if ($this->issueDate && $this->dueDate && $this->dueDate < $this->issueDate) {
            $context->buildViolation('Due date cannot be earlier than issue date.')
                ->atPath('dueDate')
                ->addViolation();
        }

        if ($this->netAmount !== null && $this->vatAmount !== null && $this->grossAmount !== null) {
            $calculatedGross = bcadd($this->netAmount, $this->vatAmount, 2);
            if (bccomp($this->grossAmount, $calculatedGross, 2) !== 0) {
                $context->buildViolation("Gross amount must be exactly equal to net amount + VAT amount ($calculatedGross).")
                    ->atPath('grossAmount')
                    ->addViolation();
            }
        }
    }
}
