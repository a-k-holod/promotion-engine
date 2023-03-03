<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\LowestPriceFilter;
use App\Tests\ServiceTestCase;

class LowestPriceFilterTest extends ServiceTestCase
{

  /** @test */
  public function isLowestPricePromotionsFilteringAppliedCorrectly(): void
  {

    $product = new Product();
    $product->setPrice(100);


    $enquiry = new LowestPriceEnquiry();
    $enquiry->setProduct($product);
    $enquiry->setQuantity(5);


    $promotions = $this->promotionsDataProvider();

    $lowestPriceFilter = $this->container->get(LowestPriceFilter::class);
//    dd($lowestPriceFilter);

    $filterEnquiry = $lowestPriceFilter->apply($enquiry, ...$promotions);

    $this->assertSame(100, $filterEnquiry->getPrice());
    $this->assertSame(250, $filterEnquiry->getDiscountedPrice());
    $this->assertSame('After February half price sale', $filterEnquiry->getPromotionName());
  }

  public function promotionsDataProvider():array
  {
    $promotionOne = new Promotion();
    $promotionOne->setName('Limited time only SALE');
    $promotionOne->setAdjustment(0.5);
    $promotionOne->setCriteria(["from" => "2023-03-01", "to" => "2023-06-06"]);
    $promotionOne->setType('date_range_multiplier');

    $promotionTwo = new Promotion();
    $promotionTwo->setName('Voucher XYZabc123');
    $promotionTwo->setAdjustment(100);
    $promotionTwo->setCriteria(["code" => "XYZabc123"]);
    $promotionTwo->setType("fixed_price_voucher");

    $promotionThree = new Promotion();
    $promotionThree->setName("Pay for one, get two!");
    $promotionThree->setAdjustment(0.5);
    $promotionThree->setCriteria(["minimum_qty" => 2]);
    $promotionThree->setType("even_items_multiplier");

    return [$promotionOne, $promotionTwo, $promotionThree,
    ];
  }
}