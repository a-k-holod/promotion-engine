<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class DateRangeMultiplier implements PriceModifierInterface
{
  public function modify(int $price, int $qty, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
  {
//    dd($enquiry);

    $requestDate = date_create($enquiry->getRequestDate());
    $from = date_create($promotion->getCriteria()['from']);
    $to = date_create($promotion->getCriteria()['to']);

    if (!($requestDate >= $from && $requestDate < $to)) {
      return $price * $qty;
    }
    return ($price * $qty) * $promotion->getAdjustment();
  }

}