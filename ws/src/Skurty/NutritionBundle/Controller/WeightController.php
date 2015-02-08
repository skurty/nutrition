<?php

namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;
use Symfony\Component\HttpFoundation\Request;

class WeightController extends ControllerCore
{
    public function indexAction(Application $app)
    {
        $repository = new $this->repository($app['db']);

        $items = $repository->findAll();

        $nbItems = count($items);

        for ($i = 0; $i < $nbItems; $i++) {
            $items[$i]['id']     = (int)$items[$i]['id'];
            $items[$i]['weight'] = (float)$items[$i]['weight'];
        }

        return $app->json($items);
    }

    public function addAction(Application $app, Request $request)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        if (isset($params['weight'], $params['weight']['date'], $params['weight']['weight'])) {
            $data = $params['weight'];
            
            return $app->json($repository->insert($data));
        } else {
            return $app->json(false);
        }
    }

    public function editAction(Application $app, Request $request, $id)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        if (isset($params['weight'], $params['weight']['date'], $params['weight']['weight'])) {
            $data = $params['weight'];
            
            return $app->json($repository->update($id, $data));
        } else {
            return $app->json(false);
        }
    } 
}