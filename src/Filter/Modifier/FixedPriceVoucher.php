<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class FixedPriceVoucher implements PriceModifierInterface
{
  public function modify(int $price, int $qty, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
  {
    $finalPrice = $promotion->getAdjustment();
    if ($enquiry->getVoucherCode() == $promotion->getCriteria()['code']) {
      return $finalPrice * $qty;
    }
    return $price * $qty;
  }
}