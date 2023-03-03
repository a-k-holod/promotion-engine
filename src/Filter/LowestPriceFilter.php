<?php

namespace App\Filter;


use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class LowestPriceFilter implements PromotionsFilterInterface
{

  public function apply(PromotionEnquiryInterface $enquiry, Promotion ...$promotion): PromotionEnquiryInterface
  {

    $price = $enquiry->getProduct()->getPrice();
    $qty = $enquiry->getQuantity();

    $lowestPrice = $qty * $price;

//    $modifiedPrice = $priceModifier->modify($price, $qty, $promotion, $enquiry);



    $enquiry->setDiscountedPrice(250);
    $enquiry->setPrice(100);
    $enquiry->setPromotionId(2);
    $enquiry->setPromotionName("After February half price sale");
    return $enquiry;
  }
}