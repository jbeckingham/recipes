<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Ingredients;
use App\Services\Cupboard;

class CupboardController Extends Controller
{
    public function __construct(Cupboard $cupboardHelper)
    {
        $this->cupboardHelper = $cupboardHelper;
    }
     /**
      * @Route("/cupboard")
      */
    public function cupboard()
    {
        $repository = $this->getDoctrine()->getRepository(Ingredients::class);
        $ingredients = $repository->findAll();

        $fresh = array_filter($ingredients, function($v){
            return $v->getType() == Cupboard::INGREDIENT_FRESH_ID;
        });

        $cupboard = array_filter($ingredients, function($v){
            return $v->getType() == Cupboard::INGREDIENT_CUPBOARD_ID;
        });

        return $this->render('recipes/cupboard.html.twig', array(
            'cupboarditems' => $cupboard,
            'freshitems' => $fresh,
        ));
    }

    /**
     * @Route("/cupboard/gettable")
     * @Method("GET")
     * @return Response
     */
    public function getTable(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Ingredients::class);
        $ingredientType = $request->query->get('ingredientType');
        $ingredientTypeId = $this->cupboardHelper->getIngredientTypeId($ingredientType);
        $ingredients = $repository->findBy(['type' => $ingredientTypeId]);

        return $this->render('recipes/ingredientTable.html.twig', array(
            'items' => $ingredients,
            'ingredientType' => $ingredientType
        ));
    }

    /**
     * @Route("/cupboard/add")
     * @Method("POST")
     * @return Response
    */
    public function addIngredient(Request $request)
    {
        $name = $request->request->get('name');
        $amount = $request->request->get('amount');
        $ingredientType = $request->request->get('ingredientType');

        $typeId = $this->cupboardHelper->getIngredientTypeId($ingredientType);

        $em = $this->getDoctrine()->getManager();

        $product = new Ingredients();
        $product->setName($name);
        $product->setAmount($amount);
        $product->setType($typeId);

        $em->persist($product);
        $em->flush();

        return new Response("OK");
    }

    /**
     * @Route("/cupboard/delete")
     * @Method("POST")
     * @return Response
    */
    public function deleteIngredient(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository(Ingredients::class)->find($id);

        $em->remove($item);
        $em->flush();

        return new Response('OK');

    }

}
?>
