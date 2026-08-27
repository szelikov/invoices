<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[Route('/api/invoices', name: 'api_invoices_')]
class InvoiceApiController extends AbstractController
{
    private const SERIALIZATION_CONTEXT = [
        AbstractNormalizer::GROUPS => ['invoice:read']
    ];

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly InvoiceRepository $repository
    ) {}

    // 1. GET /api/invoices — Получение списка инвойсов (Doctrine Paginator)
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): JsonResponse 
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = $request->query->getInt('limit', 10);
        $limit = min($limit, 100); // Защита от DoS-выборок

        $queryBuilder = $this->repository->createQueryBuilder('i')
            ->orderBy('i.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        
        $paginator = new Paginator($queryBuilder->getQuery());
        $totalItems = count($paginator);
        $totalPages = (int) ceil($totalItems / $limit);
        
        return $this->json([
            'data' => iterator_to_array($paginator->getIterator()),
            'meta' => [
                'offset' => $offset,
                'limit' => $limit,
                'total' => $totalItems,
                'totalPages' => $totalPages,
            ]
        ], Response::HTTP_OK, [], self::SERIALIZATION_CONTEXT);
    }

    // 2. GET /api/invoices/{id} — Получение одного инвойса по его UUID
    #[Route('/{id}', name: 'show', requirements: ['id' => Requirement::UUID], methods: ['GET'])]
    public function show(Invoice $invoice): JsonResponse
    {
        return $this->json($invoice, Response::HTTP_OK, [], self::SERIALIZATION_CONTEXT);
    }

    // 3. PUT /api/invoices/{id} — Полное обновление инвойса по его UUID
    #[Route('/{id}', name: 'update', requirements: ['id' => Requirement::UUID], methods: ['PUT'])]
    public function update(
        Invoice $invoice,
        #[MapRequestPayload] UpdateInvoiceDto $dto,
        InvoiceService $invoiceService
    ): JsonResponse {
        $updatedInvoice = $invoiceService->updateInvoice($invoice, $dto);

        return $this->json($updatedInvoice, Response::HTTP_OK, [], self::SERIALIZATION_CONTEXT);
    }

    // 4. POST /api/invoices — Создание нового инвойса
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            serializationContext: [AbstractNormalizer::GROUPS => ['invoice:create']]
        )] Invoice $invoice
    ): JsonResponse {
        $this->em->persist($invoice);
        $this->em->flush();

        return $this->json($invoice, Response::HTTP_CREATED, [], self::SERIALIZATION_CONTEXT);
    }
}
