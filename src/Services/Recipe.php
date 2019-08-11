<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\RecipeIngredient;
use App\Entity\Ingredients;

class Recipe
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getRecipeIngredients($recipeId)
    {
        $repository = $this->em->getRepository(RecipeIngredient::class);
        $ingredientsDb = $repository->findBy(
            array('recipe_id' => $recipeId)
        );
        $ingredients = [];
        foreach ($ingredientsDb as $ingredient) {
            $owned = $this->ownIngredient($ingredient->getIngredient());
            $item['name'] = $ingredient->getIngredient();
            $item['amount'] = $ingredient->getAmount();
            $item['owned'] = $owned;
            $ingredients[] = $item;
        }
        return $ingredients;

    }


    private function ownIngredient($ingredient)
    {
        $repository = $this->em->getRepository(Ingredients::class);
        $cupboardIngredients = $repository->findAll();
        foreach ($cupboardIngredients as $item) {
            if (trim($ingredient) == trim($item->getName())) {
                return 1;
            }
        }
        return 0;
    }

    public function getNumberRequiredIngredients($ingredients)
    {
        $ingredientsOwned = 0;
        $repository = $this->em->getRepository(Ingredients::class);
        $cupboardIngredients = $repository->findAll();
        $totalIngredients = sizeof($ingredients);
        foreach ($ingredients as $ingredient) {
            foreach ($cupboardIngredients as $cupboardIngredient) {
                if (trim($ingredient['name']) == trim($cupboardIngredient->getName())) {
                    $ingredientsOwned++;
                }
            }
        }
        return $totalIngredients - $ingredientsOwned;
    }

    public function getColour($numberOfIngredients)
    {
        if ($numberOfIngredients == 0)
        {
            return "green";
        }
        elseif ($numberOfIngredients <= 3)
        {
            return "amber";
        }
        return "red";
    }

    public function addIngredients($recipeId, $ingredients)
    {
        foreach ($ingredients as $ingredient) {

            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->setIngredient($ingredient['ingredient']);
            $recipeIngredient->setRecipeId($recipeId);
            $recipeIngredient->setAmount($ingredient['amount']);

            $this->em->persist($recipeIngredient);
            $this->em->flush();
        }

    }

}
