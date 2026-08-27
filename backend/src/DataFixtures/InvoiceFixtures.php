<?php

namespace App\DataFixtures;

use App\Entity\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InvoiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');
        $statuses = ['pending', 'approved', 'rejected'];
        $currencies = ['UAH', 'USD', 'EUR'];

        for ($i = 1; $i <= 200; $i++) {
            $invoice = new Invoice();

            // 1. FORCIBLY SET DATES TO BYPASS DOCTRINE LIFECYCLE ISSUES
            $now = new \DateTimeImmutable();
            $invoice->setCreatedAt($now);
            $invoice->setUpdatedAt($now);
            
            // Форматируем номер: например, INV-2026-00001
            $invoice->setNumber('INV-2026-' . str_pad((string)$i, 5, '0', STR_PAD_LEFT));
            
            // Название компании-поставщика
            $invoice->setSupplierName($faker->company());
            
            // Случайный ИНН / Код ЕГРПОУ (8-10 цифр)
            $invoice->setSupplierTaxId((string)$faker->numberBetween(10000000, 99999999));
            
            // Генерируем финансовые суммы
            $netAmount = $faker->randomFloat(2, 100, 50000); // от 100 до 50 000
            $vatAmount = round($netAmount * 0.20, 2);        // НДС 20%
            $grossAmount = $netAmount + $vatAmount;
            
            $invoice->setNetAmount((string)$netAmount);
            $invoice->setVatAmount((string)$vatAmount);
            $invoice->setGrossAmount((string)$grossAmount);
            
            // Случайная валюта и статус
            $invoice->setCurrency($faker->randomElement($currencies));
            $invoice->setStatus($faker->randomElement($statuses));
            
            // Случайные даты в пределах текущего года
            $issueDate = $faker->dateTimeThisYear();
            // $issueDate = \DateTimeImmutable::createFromMutable($faker->dateTimeThisYear());
            $dueDate = $issueDate->modify('+' . $faker->numberBetween(7, 30) . ' days');
            
            $invoice->setIssueDate($issueDate);
            $invoice->setDueDate($dueDate);

            $manager->persist($invoice);
            
            // Сбрасываем пакеты в БД каждые 50 записей, чтобы не перегружать память
            if ($i % 50 === 0) {
                $manager->flush();
                $manager->clear(); // Очищает память Doctrine от сохраненных объектов
            }
        }

        $manager->flush();
    }
}
