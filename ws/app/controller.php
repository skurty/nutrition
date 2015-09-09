<?php
$root = '/ws';

$app->get($root . '/', function () {
	return '';
});

$app->get($root . '/brands.json', 'Skurty\NutritionBundle\Controller\BrandController::indexAction');
$app->post($root . '/brand.json', 'Skurty\NutritionBundle\Controller\BrandController::addAction');
$app->put($root . '/brand/{id}.json', 'Skurty\NutritionBundle\Controller\BrandController::editAction');
$app->delete($root . '/brand/{id}.json', 'Skurty\NutritionBundle\Controller\BrandController::deleteAction');

$app->get($root . '/diaries.json', 'Skurty\NutritionBundle\Controller\DiaryController::indexParamsAction');
$app->get($root . '/{date}/diaries.json', 'Skurty\NutritionBundle\Controller\DiaryController::indexParamsAction');
$app->get($root . '/diary/{id}.json', 'Skurty\NutritionBundle\Controller\DiaryController::viewAction');
$app->post($root . '/diary.json', 'Skurty\NutritionBundle\Controller\DiaryController::addAction');
$app->put($root . '/diary/{id}.json', 'Skurty\NutritionBundle\Controller\DiaryController::editAction');
$app->delete($root . '/diary/{id}.json', 'Skurty\NutritionBundle\Controller\DiaryController::deleteAction');
$app->post($root . '/diary/{id}/copy.json', 'Skurty\NutritionBundle\Controller\DiaryController::copyAction');
$app->put($root . '/diary/{id}/move.json', 'Skurty\NutritionBundle\Controller\DiaryController::moveAction');
$app->post($root . '/diary/copy-meal.json', 'Skurty\NutritionBundle\Controller\DiaryController::copyMealAction');
$app->post($root . '/diary/copy-day.json', 'Skurty\NutritionBundle\Controller\DiaryController::copyDayAction');

$app->get($root . '/foods.json', 'Skurty\NutritionBundle\Controller\FoodController::indexAction');
$app->get($root . '/foods/list.json', 'Skurty\NutritionBundle\Controller\FoodController::listAction');
$app->get($root . '/foods/search.json', 'Skurty\NutritionBundle\Controller\FoodController::searchAction');
$app->post($root . '/food.json', 'Skurty\NutritionBundle\Controller\FoodController::addAction');
$app->get($root . '/food/{id}.json', 'Skurty\NutritionBundle\Controller\FoodController::viewAction');
$app->put($root . '/food/{id}.json', 'Skurty\NutritionBundle\Controller\FoodController::editAction');
$app->delete($root . '/food/{id}.json', 'Skurty\NutritionBundle\Controller\FoodController::deleteAction');

$app->get($root . '/recipes.json', 'Skurty\NutritionBundle\Controller\RecipeController::indexAction');
$app->get($root . '/recipes/list.json', 'Skurty\NutritionBundle\Controller\RecipeController::listAction');
$app->post($root . '/recipe.json', 'Skurty\NutritionBundle\Controller\RecipeController::addAction');
$app->get($root . '/recipe/{id}.json', 'Skurty\NutritionBundle\Controller\RecipeController::viewAction');
$app->put($root . '/recipe/{id}.json', 'Skurty\NutritionBundle\Controller\RecipeController::editAction');
$app->delete($root . '/recipe/{id}.json', 'Skurty\NutritionBundle\Controller\RecipeController::deleteAction');

$app->post($root . '/food_recipe.json', 'Skurty\NutritionBundle\Controller\FoodRecipeController::addAction');
$app->delete($root . '/food_recipe/{id}.json', 'Skurty\NutritionBundle\Controller\FoodRecipeController::deleteAction');

$app->get($root . '/goals.json', 'Skurty\NutritionBundle\Controller\GoalController::indexAction');
$app->post($root . '/goal.json', 'Skurty\NutritionBundle\Controller\GoalController::addAction');
$app->get($root . '/goal/{id}.json', 'Skurty\NutritionBundle\Controller\GoalController::viewAction');
$app->put($root . '/goal/{id}.json', 'Skurty\NutritionBundle\Controller\GoalController::editAction');
$app->delete($root . '/goal/{id}.json', 'Skurty\NutritionBundle\Controller\GoalController::deleteAction');

$app->get($root . '/weights.json', 'Skurty\NutritionBundle\Controller\WeightController::indexAction');
$app->post($root . '/weight.json', 'Skurty\NutritionBundle\Controller\WeightController::addAction');
$app->get($root . '/weight/{id}.json', 'Skurty\NutritionBundle\Controller\WeightController::viewAction');
$app->put($root . '/weight/{id}.json', 'Skurty\NutritionBundle\Controller\WeightController::editAction');
$app->delete($root . '/weight/{id}.json', 'Skurty\NutritionBundle\Controller\WeightController::deleteAction');

$app->get($root . '/statistics.json', 'Skurty\NutritionBundle\Controller\StatisticController::indexParamsAction');

$app->get($root . '/shoppings.json', 'Skurty\NutritionBundle\Controller\ShoppingController::indexAction');
$app->post($root . '/shopping.json', 'Skurty\NutritionBundle\Controller\ShoppingController::addAction');

// tmp
$app->get($root . '/update_diaries.json', 'Skurty\NutritionBundle\Controller\DiaryController::updateFoodIdAction');
