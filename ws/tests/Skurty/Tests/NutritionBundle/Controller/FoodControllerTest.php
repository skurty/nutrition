<?php

namespace Skurty\Tests\NutritionBundle\Controller;

use Skurty\Tests\NutritionBundle\Core\ControllerCoreTest;

class FoodControllerTest extends ControllerCoreTest
{
    protected $listUrl   = '/foods.json';
    protected $deleteUrl = '/food/3.json';

    public function testList()
    {
        $foods = array(
            array(
                'id'            => 1,
                'name'          => 'Amandes',
                'quantity'      => 100,
                'unit'          => 'g',
                'calories'      => 578,
                'proteins'      => 19,
                'carbohydrates' => 4,
                'lipids'        => 54,
                'brand_id'      => null,
                'brand_name'    => null
            ),
            array(
                'id'            => 2,
                'name'          => 'Blanc de poulet',
                'quantity'      => 1,
                'unit'          => 'tranche',
                'calories'      => 32,
                'proteins'      => 6.3,
                'carbohydrates' => 0.3,
                'lipids'        => 0.6,
                'brand_id'      => 1,
                'brand_name'    => 'Carrefour'
            )
        );

        $this->checkList($foods);
    }

    public function testAdd()
    {
        $food = array(
            'food' => array(
                'name'          => 'Carottes',
                'quantity'      => 100,
                'unit'          => 'g',
                'calories'      => 123,
                'proteins'      => 4,
                'carbohydrates' => 5,
                'lipids'        => 6
            )
        );

        $data = $this->request('POST', '/food.json', $food);

        // Test content
        $this->assertEquals(1, $data);

        // Check if the food was added to the list
        $foods = array(
            array(
                'id'            => 1,
                'name'          => 'Amandes',
                'quantity'      => 100,
                'unit'          => 'g',
                'calories'      => 578,
                'proteins'      => 19,
                'carbohydrates' => 4,
                'lipids'        => 54,
                'brand_id'      => null,
                'brand_name'    => null
            ),
            array(
                'id'            => 2,
                'name'          => 'Blanc de poulet',
                'quantity'      => 1,
                'unit'          => 'tranche',
                'calories'      => 32,
                'proteins'      => 6.3,
                'carbohydrates' => 0.3,
                'lipids'        => 0.6,
                'brand_id'      => 1,
                'brand_name'    => 'Carrefour'
            ),
            array(
                'id'            => 3,
                'name'          => 'Carottes',
                'quantity'      => 100,
                'unit'          => 'g',
                'calories'      => 123,
                'proteins'      => 4,
                'carbohydrates' => 5,
                'lipids'        => 6,
                'brand_id'      => null,
                'brand_name'    => null
            )
        );

        $this->checkList($foods);
    }

    public function testEdit()
    {
        $food = array(
            'food' => array(
                'name'          => 'Pomme',
                'quantity'      => 123,
                'unit'          => 'g',
                'calories'      => 4,
                'proteins'      => 5,
                'carbohydrates' => 6,
                'lipids'        => 7
            )
        );

        $data = $this->request('PUT', '/food/3.json', $food);

        // Test content
        $this->assertEquals(1, $data);

        // Check if the food was edited
        $foods = array(
            array(
                'id'            => 1,
                'name'          => 'Amandes',
                'quantity'      => 100,
                'unit'          => 'g',
                'calories'      => 578,
                'proteins'      => 19,
                'carbohydrates' => 4,
                'lipids'        => 54,
                'brand_id'      => null,
                'brand_name'    => null
            ),
            array(
                'id'            => 2,
                'name'          => 'Blanc de poulet',
                'quantity'      => 1,
                'unit'          => 'tranche',
                'calories'      => 32,
                'proteins'      => 6.3,
                'carbohydrates' => 0.3,
                'lipids'        => 0.6,
                'brand_id'      => 1,
                'brand_name'    => 'Carrefour'
            ),
            array(
                'id'            => 3,
                'name'          => 'Pomme',
                'quantity'      => 123,
                'unit'          => 'g',
                'calories'      => 4,
                'proteins'      => 5,
                'carbohydrates' => 6,
                'lipids'        => 7,
                'brand_id'      => null,
                'brand_name'    => null
            )
        );

        $this->checkList($foods);
    }
}
