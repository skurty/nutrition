<?php

namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;
use Symfony\Component\HttpFoundation\Request;

class GoalController extends ControllerCore
{
    public function addAction(Application $app, Request $request)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        if (isset($params['goal'], $params['goal']['date'], $params['goal']['calories'], $params['goal']['proteins'],
            $params['goal']['carbohydrates'], $params['goal']['lipids'])) {
            $data = $params['goal'];
            
            return $app->json($repository->insert($data));
        } else {
            return $app->json(false);
        }
    }

    public function editAction(Application $app, Request $request, $id)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        if (isset($params['goal'], $params['goal']['date'], $params['goal']['calories'], $params['goal']['proteins'],
            $params['goal']['carbohydrates'])) {
            $data = $params['goal'];
            
            return $app->json($repository->update($id, $data));
        } else {
            return $app->json(false);
        }
    }
}