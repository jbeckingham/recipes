<?php

namespace App\Services;


class Cupboard {

    public function getIngredientTypeId($ingredientType) {
        return ($ingredientType == 'fresh') ? 1 : 2;
    }

}
