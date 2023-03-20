<?php

namespace App\Helpers;

use App\Entity\Fruit;
use Doctrine\ORM\Tools\Pagination\Paginator;

class NutritionSummary
{
    public static function summary(Paginator $paginator): array
    {
        $nutritionSummary = [];
        foreach ($paginator->getIterator()->getArrayCopy() as $fruit) {
            /** @var Fruit $fruit */
            foreach ($fruit->getNutriotions() as $key => $nutrition) {
                if (empty($nutritionSummary[$key])) {
                    $nutritionSummary[$key] = 0;
                }

                $nutritionSummary[$key] += $nutrition;
            }
        }

        return $nutritionSummary;
    }
}
