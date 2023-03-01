<?php

namespace App\Filter;


use App\DTO\PromotionEnquiryInterface;

class LowestPriceFilter implements PromotionsFilterInterface
{

  public function apply(PromotionEnquiryInterface $enquiry): PromotionEnquiryInterface
  {
    $enquiry->setDiscountedPrice(50);
    $enquiry->setPrice(110);
    $enquiry->setPromotionId(2);
    $enquiry->setPromotionName("After February half price sale");
    return $enquiry;
  }
}