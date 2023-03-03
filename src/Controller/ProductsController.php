<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{

  public function __construct(private ProductRepository $repository,
  private EntityManagerInterface $entityManager
  )
  {
  }

  #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
  public function lowestPractice(
    Request $request,
    int $id,
    DTOSerializer $serializer,
    PromotionsFilterInterface $promotionsFilter
  ): Response
  {

    if ($request->headers->has('force_fail')) {
      return new JsonResponse([
        'error' => "Promotion doesn't compute! FAIL",
      ],
      $request->headers->get('force_fail')
      );
    }

    /** @var LowestPriceEnquiry $lowestPriceEnquiry */
    $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');


    $product = $this->repository->find($id);
//    dd($product);
    $promotions = $this->entityManager->getRepository(Promotion::class)->findValidForProduct(
      $product,
      date_create_immutable($lowestPriceEnquiry->getRequestDate())
    );

    $lowestPriceEnquiry->setProduct($product);
    $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);



    $responseContent = $serializer->serialize($modifiedEnquiry, 'json');
    return new Response($responseContent, 200,['Content-Type' => 'application/json']);

  }

  #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
  public function promotions()
  {

  }
}