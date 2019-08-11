<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Ingredients;

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
            if ($ingredient->getType() == 1) {
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
