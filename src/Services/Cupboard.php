<?php

namespace App\Services;

class Cupboard {

    const INGREDIENT_FRESH_ID = 1;
    const INGREDIENT_CUPBOARD_ID = 2;

    public function getIngredientTypeId($ingredientType) {
        return ($ingredientType == 'fresh') ? self::INGREDIENT_FRESH_ID : self::INGREDIENT_CUPBOARD_ID;
    }

}
