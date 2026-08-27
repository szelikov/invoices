<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid; 

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Invoice
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[Groups(['invoice:read'])]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Groups(['invoice:read', 'invoice:create'])]
    private ?string $number = null;

    #[ORM\Column(name: 'supplier_name', length: 255)]
    #[Groups(['invoice:read', 'invoice:create'])]
    #[Assert\NotBlank(message: "Supplier name cannot be blank.")]
    #[Assert\Length(max: 255)]
    private ?string $supplierName = null;

    #[ORM\Column(name: 'supplier_tax_id', length: 50)]
    #[Groups(['invoice:read', 'invoice:create'])]
    private ?string $supplierTaxId = null;

    #[ORM\Column(name: 'net_amount', type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['invoice:read', 'invoice:create'])]
    private ?string $netAmount = null;

    #[ORM\Column(name: 'vat_amount', type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['invoice:read', 'invoice:create'])]
    private ?string $vatAmount = null;

    #[ORM\Column(name: 'gross_amount', type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['invoice:read', 'invoice:create'])]
    #[Assert\NotBlank(message: "Gross amount is required.")]
    #[Assert\PositiveOrZero(message: "Gross amount must be positive or zero.")]
    private ?string $grossAmount = null;

    #[ORM\Column(length: 3)]
    #[Groups(['invoice:read', 'invoice:create'])]
    private ?string $currency = null;

    #[ORM\Column(length: 20)]
    #[Groups(['invoice:read', 'invoice:create'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['pending', 'approved', 'rejected'], message: "Invalid status.")]
    private ?string $status = null;

    #[ORM\Column(name: 'issue_date', type: Types::DATE_MUTABLE)]
    #[Groups(['invoice:read', 'invoice:create'])]
    private ?\DateTime $issueDate = null;

    #[ORM\Column(name: 'due_date', type: Types::DATE_MUTABLE)]
    #[Groups(['invoice:read', 'invoice:create'])]
    private ?\DateTime $dueDate = null;

    #[ORM\Column(name: 'created_at')]
    #[Groups(['invoice:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at')]
    #[Groups(['invoice:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getSupplierName(): ?string
    {
        return $this->supplierName;
    }

    public function setSupplierName(string $supplierName): static
    {
        $this->supplierName = $supplierName;

        return $this;
    }

    public function getSupplierTaxId(): ?string
    {
        return $this->supplierTaxId;
    }

    public function setSupplierTaxId(string $supplierTaxId): static
    {
        $this->supplierTaxId = $supplierTaxId;

        return $this;
    }

    public function getNetAmount(): ?string
    {
        return $this->netAmount;
    }

    public function setNetAmount(string $netAmount): static
    {
        $this->netAmount = $netAmount;

        return $this;
    }

    public function getVatAmount(): ?string
    {
        return $this->vatAmount;
    }

    public function setVatAmount(string $vatAmount): static
    {
        $this->vatAmount = $vatAmount;

        return $this;
    }

    public function getGrossAmount(): ?string
    {
        return $this->grossAmount;
    }

    public function setGrossAmount(string $grossAmount): static
    {
        $this->grossAmount = $grossAmount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIssueDate(): ?\DateTime
    {
        return $this->issueDate;
    }

    public function setIssueDate(\DateTime $issueDate): static
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTime $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
