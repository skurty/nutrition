<?php

namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;
use Symfony\Component\HttpFoundation\Request;

class RecipeController extends ControllerCore
{
    public function indexAction(Application $app)
    {
        $repository = new $this->repository($app['db']);

        $items = $repository->findAll();

        $nbItems = count($items);

        // parse float
        for ($i = 0; $i < $nbItems; $i++) {
            $items[$i]['calories']      = (float)$items[$i]['calories'];
            $items[$i]['proteins']      = (float)$items[$i]['proteins'];
            $items[$i]['carbohydrates'] = (float)$items[$i]['carbohydrates'];
            $items[$i]['lipids']        = (float)$items[$i]['lipids'];
        }

        return $app->json($items);
    }
    
    public function listAction(Application $app)
    {
        $repository = new $this->repository($app['db']);

        $recipes = $repository->findList();

        return $app->json(array('recipes' => $recipes));
    }

    public function viewAction(Application $app, $id)
    {
        $repository = new $this->repository($app['db']);

        $item = $repository->findById($id);

        // parse float
        $item['id']            = (int)$item['id'];
        $item['calories']      = (float)$item['calories'];
        $item['proteins']      = (float)$item['proteins'];
        $item['carbohydrates'] = (float)$item['carbohydrates'];
        $item['lipids']        = (float)$item['lipids'];

        $nbFoods = count($item['foods']);

        // parse float
        for ($i = 0; $i < $nbFoods; $i++) {
            $item['foods'][$i]['id']            = (int)$item['foods'][$i]['id'];
            $item['foods'][$i]['quantity']      = (float)$item['foods'][$i]['quantity'];
            $item['foods'][$i]['calories']      = (float)$item['foods'][$i]['calories'];
            $item['foods'][$i]['proteins']      = (float)$item['foods'][$i]['proteins'];
            $item['foods'][$i]['carbohydrates'] = (float)$item['foods'][$i]['carbohydrates'];
            $item['foods'][$i]['lipids']        = (float)$item['foods'][$i]['lipids'];
        }

        return $app->json($item);
    }
    
    public function addAction(Application $app, Request $request)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        if (isset($params['name'])) {
            $data = array('name' => $params['name']);

            $repository->insert($data);

            $recipe = array(
                'id'            => $app['db']->lastInsertId(),
                'name'          => $params['name'],
                'calories'      => 0,
                'proteins'      => 0,
                'carbohydrates' => 0,
                'lipids'        => 0,
                'foods'         => array()
            );
            
            $res = $recipe;
        } else {
            $res = array('error' => 'Wrong parameters');
        }

        return $app->json($res);
    }
}