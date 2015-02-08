<?php
namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;

use Symfony\Component\HttpFoundation\Request;

class BrandController extends ControllerCore
{
    public function addAction(Application $app, Request $request)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        $res = null;

        if (isset($params['name'])) {
            // Check if a brand with this name already exists
            if ($repository->findCountByName($params['name']) == 0) {
                $res = $repository->insert(array('name' => $params['name']));
            } else {
                $res = array('error' => 'Brand already exists');
            }
        } else {
            $res = array('error' => 'Wrong parameters');
        }

        return $app->json($res);
    }

    public function editAction(Application $app, Request $request, $id)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        if (isset($params['name'])) {
            // Check if a brand with this name already exists
            if ($repository->findCountByName($params['name'], $id) == 0) {
                $res = $repository->update($id, array('name' => $params['name']));
            } else {
                $res = array('error' => 'A brand with the same name already exists');
            }
        } else {
            $res = array('error' => 'Wrong parameters');
        }

        return $app->json($res);
    }
}