<?php

namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;
use Skurty\NutritionBundle\Repository\BrandRepository;
use Skurty\NutritionBundle\Repository\FoodRepository;
use Skurty\NutritionBundle\Repository\RecipeRepository;
use Skurty\NutritionBundle\Repository\GoalRepository;
use Symfony\Component\HttpFoundation\Request;

class DiaryController extends ControllerCore
{
    public function indexParamsAction(Application $app, $date = null, $last = null)
    {
        $repository = new $this->repository($app['db']);

        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        return $app->json($this->getDiaries($app, $repository, $date, $last));
    }

    public function addAction(Application $app, Request $request)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        $diary = array();

        if ($request->request->get('date')) {
            $date = $request->request->get('date');
        } elseif (isset($params['date'])) {
            $date = $params['date'];
        } else {
            $date = date('Y-m-d');
        }

        $type = null;
        if ($request->request->get('food') && $request->request->get('quantity') && $request->request->get('meal')) {
            $type     = 'food';
            $food     = $request->request->get('food');
            $quantity = $request->request->get('quantity');
            $meal     = $request->request->get('meal');
        } elseif (isset($params['food'], $params['quantity'], $params['meal'])) {
            $type     = 'food';
            $food     = $params['food'];
            $quantity = $params['quantity'];
            $meal     = $params['meal'];
        } elseif ($request->request->get('recipe') && $request->request->get('quantity') && $request->request->get('meal')) {
            $type     = 'recipe';
            $recipe   = $request->request->get('recipe');
            $quantity = $request->request->get('quantity');
            $meal     = $request->request->get('meal');
        } elseif (isset($params['recipe'], $params['quantity'], $params['meal'])) {
            $type     = 'recipe';
            $recipe   = $params['recipe'];
            $quantity = $params['quantity'];
            $meal     = $params['meal'];
        }

        if ($type == 'food') {
            // Add a food
            
            $foodRepository = new FoodRepository($app['db']);

            $diary = $foodRepository->findByIdMin($food);

            $count = $diary['count'];
            unset($diary['count']);
            $diary['calories']      *= $quantity / $diary['quantity'];
            $diary['proteins']      *= $quantity / $diary['quantity'];
            $diary['carbohydrates'] *= $quantity / $diary['quantity'];
            $diary['lipids']        *= $quantity / $diary['quantity'];
            $diary['quantity']      = $quantity;
            $diary['date']          = $date;
            $diary['meal_id']       = $meal;
            $diary['food_id']       = $food;

            if (!empty($diary['brand_id'])) {
                $brandRepository = new BrandRepository($app['db']);

                $brand = $brandRepository->findNameById($diary['brand_id']);
            }

            if ($repository->insert($diary)) {
                $diary['id'] = $app['db']->lastInsertId();

                if (!empty($diary['brand_id'])) {
                    $brandRepository = new BrandRepository($app['db']);

                    $brand = $brandRepository->findNameById($diary['brand_id']);

                    $diary['brand_name'] = $brand['name'];
                }

                $foodRepository->update($params['food'], array('count' => $count + 1));
            } else {
                $app['monolog']->addInfo('Error to save the diary with food ' . $food);
            }
        } elseif ($type == 'recipe') {

            // Add a recipe

            $recipeRepository = new RecipeRepository($app['db']);

            $diary = $recipeRepository->findByIdMin($recipe);

            $diary['calories']      *= $quantity;
            $diary['proteins']      *= $quantity;
            $diary['carbohydrates'] *= $quantity;
            $diary['lipids']        *= $quantity;
            $diary['quantity']      = $quantity;
            $diary['date']          = $date;
            $diary['meal_id']       = $meal;
            $diary['recipe_id']     = $recipe;

            if ($repository->insert($diary)) {
                $diary['id'] = $app['db']->lastInsertId();

                // $recipeRepository->update($diary['id'], array('count' => $count + 1));
            } else {
                $app['monolog']->addInfo('Error to save the diary with recipe ' . $recipe);
            }
        } elseif (isset($params['manual'], $params['meal'])) {

            // Add a manual entry

            $diary = array(
                'name'          => $params['manual']['name'],
                'quantity'      => $params['manual']['quantity'],
                'unit'          => 'g',
                'calories'      => $params['manual']['calories'],
                'proteins'      => $params['manual']['proteins'],
                'carbohydrates' => $params['manual']['carbohydrates'],
                'lipids'        => $params['manual']['lipids'],
                'date'          => $params['date'],
                'meal_id'       => $params['meal']
            );

            if ($repository->insert($diary)) {
                $diary['id'] = $app['db']->lastInsertId();
            } else {
                $app['monolog']->addInfo('Error to save the manual diary : ' . $date . ' - ' . $params['manual']);
            }
        }

        // Get the totals
        $totalMeal = $repository->findTotalMeal($params['meal'], $date);

        $total = $repository->findTotal($date);

        return $app->json(array(
            'diary'     => $diary,
            'totalMeal' => $totalMeal,
            'total'     => $total
        ));
    }

    public function editAction(Application $app, Request $request, $id)
    {
        $params = json_decode($request->getContent(), true);

        $res = null;

        if (isset($params['quantity'])) {
            $repository = new $this->repository($app['db']);

            $diary = $repository->findByIdMin($id);

            if (!empty($diary)) {
                $data = array(
                    'quantity'      => $params['quantity'],
                    'calories'      => $params['quantity'] * $diary['calories'] / $diary['quantity'],
                    'proteins'      => $params['quantity'] * $diary['proteins'] / $diary['quantity'],
                    'carbohydrates' => $params['quantity'] * $diary['carbohydrates'] / $diary['quantity'],
                    'lipids'        => $params['quantity'] * $diary['lipids'] / $diary['quantity'],
                );

                if ($app->json($repository->update($id, $data))) {
                    $totalMeal = $repository->findTotalMeal($diary['meal_id'], $diary['date']);
                    $totalMeal['calories']      = (float)$totalMeal['calories'];
                    $totalMeal['proteins']      = (float)$totalMeal['proteins'];
                    $totalMeal['carbohydrates'] = (float)$totalMeal['carbohydrates'];
                    $totalMeal['lipids']        = (float)$totalMeal['lipids'];

                    $total = $repository->findTotal($diary['date']);
                    $total['calories']      = (float)$total['calories'];
                    $total['proteins']      = (float)$total['proteins'];
                    $total['carbohydrates'] = (float)$total['carbohydrates'];
                    $total['lipids']        = (float)$total['lipids'];

                    $res = array(
                        'diary'     => $data,
                        'totalMeal' => $totalMeal,
                        'total'     => $total
                    );
                } else {
                    $app['monolog']->addInfo('Error to update the diary ' . $id);

                    $res = array('error' => 'Error to update the diary');
                }
            } else {
                $res = array('error' => 'Wrong ID');
            }
        }

        return $app->json($res);
    }

    public function deleteAction(Application $app, $id)
    {
        $repository = new $this->repository($app['db']);

        $diary = $repository->findByIdMin($id);

        if ($app->json($repository->delete($id))) {
            $totalMeal = $repository->findTotalMeal($diary['meal_id'], $diary['date']);
            $totalMeal['calories']      = (float)$totalMeal['calories'];
            $totalMeal['proteins']      = (float)$totalMeal['proteins'];
            $totalMeal['carbohydrates'] = (float)$totalMeal['carbohydrates'];
            $totalMeal['lipids']        = (float)$totalMeal['lipids'];

            $total = $repository->findTotal($diary['date']);
            $total['calories']      = (float)$total['calories'];
            $total['proteins']      = (float)$total['proteins'];
            $total['carbohydrates'] = (float)$total['carbohydrates'];
            $total['lipids']        = (float)$total['lipids'];

            return $app->json(array(
                'totalMeal' => $totalMeal,
                'total'     => $total
            ));
        } else {
            return $app->json(array('error' => 'Problem to delete the diary'));
        }
    }

    public function copyAction(Application $app, Request $request, $id)
    {
        $params = json_decode($request->getContent(), true);

        if (isset($params['meal'])) {
            $repository = new $this->repository($app['db']);

            return $app->json($repository->copy($id, $params['meal']));
        }

        return $app->json('');
    }

    public function moveAction(Application $app, Request $request, $id)
    {
        $params = json_decode($request->getContent(), true);

        if (isset($params['meal'])) {
            $repository = new $this->repository($app['db']);

            return $app->json($repository->move($id, $params['meal']));
        }

        return $app->json('');
    }

    public function copyMealAction(Application $app, Request $request)
    {
        $params = json_decode($request->getContent(), true);

        $res = null;

        if (isset($params['meal'], $params['from'], $params['to'])) {
            $repository = new $this->repository($app['db']);

            $repository->copyMeal($params['meal'], $params['from'], $params['to']);

            $diaries = $repository->findAllByMealDateMin($params['meal'], $params['to']);

            $totalMeal = $repository->findTotalMeal($params['meal'], $params['to']);

            $total = $repository->findTotal($params['to']);

            $res = array('diaries' => $diaries, 'totalMeal' => $totalMeal, 'total' => $total);
        } else {
            $res = array('error' => 'Wrong parameters');
        }

        return $app->json($res);
    }

    public function copyDayAction(Application $app, Request $request)
    {
        $params = json_decode($request->getContent(), true);

        $res = null;

        if (isset($params['from'], $params['to'])) {  
            $repository = new $this->repository($app['db']);

            $repository->copyDay($params['from'], $params['to']);

            $res = $this->getDiaries($app, $repository, $params['to']);
        } else {
            $res = array('error' => 'Wrong parameters');
        }

        return $app->json($res);
    }

    private function getDiaries($app, $repository, $date, $last = null)
    {
        $diaries = array();

        $totalMeals = array();

        $meals = array(1, 2, 3, 4, 5, 6);

        $diariesAvailable = true;

        // get count of new diaries (to know if we have to send the diaries)
        if (!is_null($last)) {
            $nbDiaries = $repository->findCountDate($date, $last);

            if ($nbDiaries == 0) {
                $diariesAvailable = false;
            }
        }

        if ($diariesAvailable) {
            foreach($meals as $m) {
                $mealDiaries = $repository->findAllByMealDate($m, $date);

                $nbMealDiaries = count($mealDiaries);
                for ($i = 0; $i < $nbMealDiaries; $i++) {
                    $mealDiaries[$i]['id']            = (int)$mealDiaries[$i]['id'];
                    $mealDiaries[$i]['quantity']      = (float)$mealDiaries[$i]['quantity'];
                    $mealDiaries[$i]['calories']      = (float)$mealDiaries[$i]['calories'];
                    $mealDiaries[$i]['proteins']      = (float)$mealDiaries[$i]['proteins'];
                    $mealDiaries[$i]['carbohydrates'] = (float)$mealDiaries[$i]['carbohydrates'];
                    $mealDiaries[$i]['lipids']        = (float)$mealDiaries[$i]['lipids'];
                }

                $diaries[$m] = $mealDiaries;
            }

            $totalMealsResult = $repository->findTotalMeals($date);

            foreach ($totalMealsResult as $r) {
                $totalMeals[$r['meal_id']] = array(
                    'calories'      => (float)$r['calories'],
                    'proteins'      => (float)$r['proteins'],
                    'carbohydrates' => (float)$r['carbohydrates'],
                    'lipids'        => (float)$r['lipids'],
                );
            }

            $total = $repository->findTotal($date);
            $total['calories']      = (float)$total['calories'];
            $total['proteins']      = (float)$total['proteins'];
            $total['carbohydrates'] = (float)$total['carbohydrates'];
            $total['lipids']        = (float)$total['lipids'];

            $goalRepository = new GoalRepository($app['db']);
            $goal = $goalRepository->findByDate($date);
            $goal['calories']      = (float)$goal['calories'];
            $goal['proteins']      = (float)$goal['proteins'];
            $goal['carbohydrates'] = (float)$goal['carbohydrates'];
            $goal['lipids']        = (float)$goal['lipids'];

            // Last date (to use the local storage)
            // $lastDiary = $repository->findLastDate($date);

            // if (isset($lastDiary[0]['updated'])) {
            //     $last = strtotime($lastDiary[0]['updated']);
            // } else {
            //     $last = null;
            // }

            return array(
                'diaries'    => $diaries,
                'totalMeals' => $totalMeals,
                'total'      => $total,
                'goal'       => $goal//,
                // 'last'       => $last
            );
        } else {
            return array();
        }
    }

    public function updateFoodIdAction(Application $app)
    {
        $repository = new $this->repository($app['db']);

        $repository->updateFoodId();

        return 'Hi';
    }
}