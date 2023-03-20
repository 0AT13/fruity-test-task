<?php

namespace App\Helpers;

use App\Entity\Fruit;

class NutritionSummary
{
    /**
     * Summary the fruits nutriotion by nutriotion key
     * 
     * @param array $data array of Fruits
     * 
     * @return array
     */
    public static function summary(array $data): array
    {
        $nutritionSummary = [];
        foreach ($data as $fruit) {
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
