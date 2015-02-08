<?php
namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;

use Symfony\Component\HttpFoundation\Request;
use Sunra\PhpSimple\HtmlDomParser;

class FoodController extends ControllerCore
{
    public function indexAction(Application $app)
    {
        $repository = new $this->repository($app['db']);
        
        $items = $repository->findAll();

        $nbItems = count($items);

        // parse float
        for ($i = 0; $i < $nbItems; $i++) {
            $items[$i]['id']            = (int)$items[$i]['id'];
            $items[$i]['quantity']      = (float)$items[$i]['quantity'];
            $items[$i]['calories']      = (float)$items[$i]['calories'];
            $items[$i]['proteins']      = (float)$items[$i]['proteins'];
            $items[$i]['carbohydrates'] = (float)$items[$i]['carbohydrates'];
            $items[$i]['lipids']        = (float)$items[$i]['lipids'];
            $items[$i]['brand_id']      = (int)$items[$i]['brand_id'];
        }

        return $app->json($items);
    }

    public function listAction(Application $app)
    {
        $repository = new $this->repository($app['db']);

        $foodsData = $repository->findAllMin();

        $foods = array();
        foreach ($foodsData as $f) {
            $name = $f['name'];
            if ($f['brand_name'] != '') {
                $name .= ' - ' . $f['brand_name'];
            }
            $foods[] = array(
                'id'    => (int)$f['id'],
                'name'  => $name
            );
        }

        return $app->json(array('foods' => $foods, 'list' => true));
    }

    public function viewAction(Application $app, $id)
    {
        $repository = new $this->repository($app['db']);

        $item = $repository->findByIdMin($id);

        return $app->json($item);
    }

    public function addAction(Application $app, Request $request)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        $res = null;

        if (isset($params['food'], $params['food']['name'], $params['food']['calories'], $params['food']['proteins'],
            $params['food']['carbohydrates'], $params['food']['lipids'], $params['food']['quantity'], $params['food']['unit'])) {
            $data = $params['food'];
            
            $res = $repository->insert($data);
        } else {
            $res = array('error' => 'Wrong parameters');
        }

        return $app->json($res);
    }

    public function editAction(Application $app, Request $request, $id)
    {
        $repository = new $this->repository($app['db']);

        $params = json_decode($request->getContent(), true);

        $res = null;

        if (isset($params['food'], $params['food']['name'], $params['food']['quantity'], $params['food']['unit'],
            $params['food']['calories'], $params['food']['proteins'], $params['food']['carbohydrates'])) {
            $data = $params['food'];
            
            $res = $repository->update($id, $data);
        } else {
            $res = array('error' => 'Wrong parameters');
        }

        return $app->json($res);
    }

    public function searchAction(Application $app, Request $request)
    {
        $foods = array();

        if (!is_null($request->query->get('search'))) {

            $html = HtmlDomParser::str_get_html($this->curl('http://www.les-calories.com/recherche.html', array('rech' => $request->query->get('search'))));

            $i = 0;
            $food = $foods = array();

            foreach($html->find('span.text3') as $e) {
                switch ($i) {
                    case 0:
                        $food['name'] = trim($e->plaintext);
                        $i++;
                        break;
                    case 1:
                        $food['calories'] = trim($e->plaintext);
                        $i++;
                        break;
                    case 2:
                        $food['proteins'] = trim($e->plaintext);
                        $i++;
                        break;
                    case 3:
                        $food['carbohydrates'] = trim($e->plaintext);
                        $i++;
                        break;
                    case 4:
                        $food['lipids'] = trim($e->plaintext);
                        $i++;
                        break;
                    case 5:
                        $quantity = trim($e->plaintext);
                        $quantityArray = explode(' ', $quantity);

                        $food['quantity'] = $quantityArray[0];

                        if (isset($quantityArray[1])) {
                            $food['unit'] = $quantityArray[1];
                        }
                        $foods[] = $food;
                        $i = 0;
                        break;
                }
            }
        }

        return $app->json(array('foods' => $foods));
    }

    private function curl($url, $post = array())
    {
        $ch = curl_init();
        
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($post)) {
            curl_setopt($ch,CURLOPT_POST, count($post));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
        }
        
        $output = curl_exec($ch);
        
        curl_close($ch);
        
        return $output;
    }
}