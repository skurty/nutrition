<?php

namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;
use Skurty\NutritionBundle\Repository\FoodRepository;
use Skurty\NutritionBundle\Repository\RecipeRepository;

use Symfony\Component\HttpFoundation\Request;

class FoodRecipeController extends ControllerCore
{
    public function addAction(Application $app, Request $request)
    {
        $params = json_decode($request->getContent(), true);

        if (isset($params['recipe'], $params['food'], $params['quantity'])) {
            $repository       = new $this->repository($app['db']);
            $foodRepository   = new FoodRepository($app['db']);
            $recipeRepository = new RecipeRepository($app['db']);

            $food = $foodRepository->findById($params['food']);

            $recipe = $recipeRepository->findById($params['recipe']);

            $foodRecipe = array(
                'quantity'      => $params['quantity'],
                'calories'      => $food['calories'] * $params['quantity'] / $food['quantity'],
                'proteins'      => $food['proteins'] * $params['quantity'] / $food['quantity'],
                'carbohydrates' => $food['carbohydrates'] * $params['quantity'] / $food['quantity'],
                'lipids'        => $food['lipids'] * $params['quantity'] / $food['quantity'],                  
                'food_id'       => $params['food'],
                'recipe_id'     => $params['recipe']
            );

            $repository->insert($foodRecipe);

            $foodRecipeId = $app['db']->lastInsertId();

            $dataRecipe = array(
                'calories'      => $recipe['calories'] + $food['calories'] * $params['quantity'] / $food['quantity'],
                'proteins'      => $recipe['proteins'] + $food['proteins'] * $params['quantity'] / $food['quantity'],
                'carbohydrates' => $recipe['carbohydrates'] + $food['carbohydrates'] * $params['quantity'] / $food['quantity'],
                'lipids'        => $recipe['lipids'] + $food['lipids'] * $params['quantity'] / $food['quantity']
            );

            $recipeRepository->update($params['recipe'], $dataRecipe);

            $foodRecipeData = array(
                'id'            => $foodRecipeId,
                'quantity'      => $params['quantity'],
                'calories'      => $foodRecipe['calories'],
                'proteins'      => $foodRecipe['proteins'],
                'carbohydrates' => $foodRecipe['carbohydrates'],
                'lipids'        => $foodRecipe['lipids'],
                'name'          => $food['name'],
                'brand_name'    => $food['brand_name']
            );

            return $app->json($foodRecipeData);
        } else {
            return $app->json(false);
        }
    }

    public function deleteAction(Application $app, $id)
    {
        // parent::delete($id);

        $repository       = new $this->repository($app['db']);
        $recipeRepository = new RecipeRepository($app['db']);

        $foodRecipe = $repository->findByIdMin($id);

        $recipe = $recipeRepository->updateFoodRecipe($foodRecipe);
    }
}