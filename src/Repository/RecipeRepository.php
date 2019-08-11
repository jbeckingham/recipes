<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     */

    public function getRecipesAndIngredients()
    {
        $ingredRepository = $this->getEntityManager()->getRepository(RecipeIngredient::class);
        foreach ($this->findAll() as $recipe) {
            $recipeId = $recipe->getId();
            $recipes['recipe'] = $recipe;
            $ingredients = $ingredRepository->findBy(
                array('recipe_id' => $recipeId)
            );
            $recipes['ingredients'] = $ingredients;
        }

        return $recipes;
    }


    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
