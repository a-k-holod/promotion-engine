<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;
use function Sodium\add;

class EvenQtyPriceModifier implements PriceModifierInterface
{

  public function modify(int $price, int $qty, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
  {
    $evenItems = floor($qty / 2);
    $additionalItem = $qty % 2;

    if ($qty <= 1) {
      return $qty * $price;
    } elseif ($additionalItem == 0) {
      return ($promotion->getAdjustment() * $price) * $qty;
    } else {
      return (($qty - $additionalItem) * $price) *$promotion->getAdjustment() + ($price * $additionalItem);
    }

  }
}