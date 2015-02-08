<?php

namespace Skurty\NutritionBundle\Core;

use Silex\Application;
use Silex\Route;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

class ControllerCore implements ControllerProviderInterface {

    protected $repository;

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function __construct() {
        $calledClass = explode('\\', get_called_class());
        $class = end($calledClass);

        $this->setRepository($this->getRepositoryFromCalledClass($calledClass));
        $this->setController(new ControllerCollection(new Route()));
    }

    private function getRepositoryFromCalledClass($calledClass)
    {
        $class = substr(end($calledClass), 0, -10);
        $repository = $calledClass[0] . "\\" . $calledClass[1] . '\\Repository\\' . $class . 'Repository';

        return $repository;
    }

    public function connect(Application $app)
    {

    }
    
    public function indexAction(Application $app)
    {
        $repository = new $this->repository($app['db']);

        $items = $repository->findAll();

        $nbItems = count($items);

        for ($i = 0; $i < $nbItems; $i++) {
            $items[$i]['id'] = (int)$items[$i]['id'];
        }

        return $app->json($items);
    }

    public function viewAction(Application $app, $id)
    {
        $repository = new $this->repository($app['db']);

        $item = $repository->findById($id);

        return $app->json($item);
    }

    public function deleteAction(Application $app, $id)
    {
        $repository = new $this->repository($app['db']);

        $res = null;

        if ($repository->findCountById($id) == 1) {
            $res = $repository->delete($id);
        } else {
            $res = array('error' => 'Incorrect ID');
        }

        return $app->json($res);;
    }
}
