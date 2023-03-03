<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

interface PriceModifierInterface
{

  public function modify(int $price, int $qty, Promotion $promotion, PromotionEnquiryInterface $enquiry): int;
}