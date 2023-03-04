<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\EvenQtyPriceModifier;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Tests\ServiceTestCase;

class PriceModifiersTest extends ServiceTestCase
{
  /** @test */
  public function DateRangeMultiplierReturnsCorrectlyModifiedPrice(): void
  {
    $enquiry = new LowestPriceEnquiry();
    $enquiry->setQuantity(5);
    $enquiry->setRequestDate("2023-03-10");

    $promotion = new Promotion();
    $promotion->setName('Limited time only SALE');
    $promotion->setAdjustment(0.5);
    $promotion->setCriteria(["from" => "2023-03-01", "to" => "2023-06-06"]);
    $promotion->setType('date_range_multiplier');

    $dateRangeModifier = new DateRangeMultiplier();

    $modifiedPrice = $dateRangeModifier->modify(100, 5, $promotion, $enquiry);

    $this->assertEquals(250, $modifiedPrice);

  }

  /** @test */
  public function fixedPriceVoucherAppliedCorrectly(): void
  {

    $promotionFixed = new Promotion();
    $promotionFixed->setName('Voucher XYZabc123');
    $promotionFixed->setAdjustment(100);
    $promotionFixed->setCriteria(["code" => "XYZabc123"]);
    $promotionFixed->setType('fixed_price_voucher');

    $fixedPriceVoucher = new FixedPriceVoucher();

    $enquiry = new LowestPriceEnquiry();
    $enquiry->setQuantity(10);
    $enquiry->setVoucherCode('XYZabc123');

    $modifiedFixedPrice = $fixedPriceVoucher->modify(150, 10, $promotionFixed, $enquiry);

    $this->assertEquals(1000, $modifiedFixedPrice);
  }

  /** @test  */
  public function checkIfEvenNumberOfItemsPromoAppliedCorrectly()
  {
    $enquiry = new LowestPriceEnquiry();
//    $enquiry->setPrice(200);
    $enquiry->setQuantity(2);


    $promo = new Promotion();
    $promo->setName("Two in price of one promo");
    $promo->setAdjustment(0.5);
    $promo->setCriteria(['min_qty' => 2]);
    $promo->setType('even_qty_half_price_promo');

    $evenPriceModifier = new EvenQtyPriceModifier();
    $modifiedIfEvenQtyPrice = $evenPriceModifier->modify(100, 5, $promo, $enquiry);

    $this->assertEquals(300, $modifiedIfEvenQtyPrice);
  }


}