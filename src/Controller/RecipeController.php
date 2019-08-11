<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\Ingredients;
use App\Services\Recipe as RecipeService;


class RecipeController Extends Controller
{

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }
     /**
      * @Route("/recipes")
      */
    public function recipes() {

        $repository = $this->getDoctrine()->getRepository(Recipe::class);
        $ingredRepository = $this->getDoctrine()->getRepository(RecipeIngredient::class);
        $recipesDb = $repository->findAll();

        foreach ($recipesDb as $item) {
            $recipeId = $item->getId();
            $ingredients = $this->recipeService->getRecipeIngredients($recipeId);
            $ingredientsRequired = $this->recipeService->getNumberRequiredIngredients($ingredients);
            $colour = $this->recipeService->getColour($ingredientsRequired);
            $add = [$item, $ingredients, $ingredientsRequired, $colour];
            $recipes[]= $add;
        }

       return $this->render('recipes/recipes.html.twig', array(
            'recipes' => $recipes,
        ));
    }

    /**
     * @Route("/recipe/add")
     * @Method("POST")
     * @return Response
    */
    public function addRecipe(Request $request)
    {
        $requestIngredients = json_decode($request->request->get('ingredients'), true);
        foreach ($requestIngredients as $requestIngredient) {
            $ingredients[] = json_decode($requestIngredient, true);
        }

        $em = $this->getDoctrine()->getManager();

        $recipe = new Recipe();
        $recipe->setName($request->request->get('name'););
        $recipe->setType($request->request->get('type'););
        $recipe->setServes($request->request->get('serves'););
        $recipe->setTime($request->request->get('time'););
        $recipe->setMethod($request->request->get('method'););

        $em->persist($recipe);
        $em->flush();

        $recipeId = $recipe->getId();
        $this->recipeService->addIngredients($recipeId, $ingredients);

        return new Response('OK');
    }

    /**
     * @Route("/recipe/delete")
     * @Method("POST")
     * @return Response
    */
    public function deleteRecipe(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository(Recipe::class)->find($id);

        $em->remove($item);
        $em->flush();

        return new Response('OK');

    }

    /**
     * @Route("/recipe/ingredients")
     * @Method("POST")
     * @return Response
    */
    public function getRecipeIngredientsHtml(Request $request) {
        $recipeId = $request->request->get('recipeId');
        $repository = $this->getDoctrine()->getRepository(Recipe::class);
        return $this->render('recipes/ingredientPopUp.html.twig', array(
             'ingredients' => $this->recipeService->getRecipeIngredients($recipeId) ,
         ));
    }

    /**
     * @Route("/recipe/method")
     * @Method("POST")
     * @return Response
    */
    public function getRecipeMethodHtml(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository(Recipe::class)->find($recipeId);
        $method = $recipe->getMethod();
        $recipeId = $request->request->get('recipeId');
        return $this->render('recipes/methodPopUp.html.twig', array(
             'method' => $method,
         ));
    }

}
?>
