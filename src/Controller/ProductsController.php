<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Service\Serializer\DTOSerializer;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{

  #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
  public function lowestPractice(int $id, Request $request, DTOSerializer $serializer): Response
  {

    if ($request->headers->has('force_fail')) {
      return new JsonResponse([
        'error' => "Promotion Engine fail error msg!",
      ],
      $request->headers->get('force_fail')
      );
    }

    /** @var LowestPriceEnquiry $lowestPriceEnquiry */
    $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');


    $lowestPriceEnquiry->setDiscountedPrice(50);
    $lowestPriceEnquiry->setPrice(110);
    $lowestPriceEnquiry->setPromotionId(2);
    $lowestPriceEnquiry->setPromotionName("Black Friday half price promo");

    $responseContent = $serializer->serialize($lowestPriceEnquiry, 'json');
    return new Response($responseContent, 200);

  }

  #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
  public function promotions()
  {

  }
}