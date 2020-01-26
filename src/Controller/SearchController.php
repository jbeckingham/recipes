<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Ingredients;
use App\Services\Cupboard;

class SearchController Extends Controller
{
     /**
      * @Route("/search")
      */
    public function search() {

        $repository = $this->getDoctrine()->getRepository(Ingredients::class);
        $ingredients = $repository->findAll();
        $fresh = [];
        $cupboard= [];

        foreach ($ingredients as $ingredient) {
            if ($ingredient->getType() == Cupboard::INGREDIENT_FRESH_ID) {
                $fresh[] = $ingredient;
            }
            else {
                $cupboard[] = $ingredient;
            }
        }

       return $this->render('recipes/search.html.twig', array(
            'freshitems' => $fresh,
            'cupboarditems' => $cupboard,
        ));
    }
}
?>
