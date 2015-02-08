<?php

namespace Skurty\Tests\NutritionBundle\Controller;

use Skurty\Tests\NutritionBundle\Core\ControllerCoreTest;

class RecipeControllerTest extends ControllerCoreTest
{
    protected $listUrl   = '/recipes.json';
    protected $deleteUrl = '/recipe/3.json';

    public function testList()
    {
        $recipes = array(
            array(
                'id'            => 1,
                'name'          => 'Crêpe',
                'calories'      => 342.15,
                'proteins'      => 28.22,
                'carbohydrates' => 27.27,
                'lipids'        => 12.98
            ),
            array(
                'id'            => 2,
                'name'          => 'Pancake au sarrasin',
                'calories'      => 966.40,
                'proteins'      => 45.60,
                'carbohydrates' => 146.00,
                'lipids'        => 20.50
            )
        );

        $this->checkList($recipes);
    }

    public function testAdd()
    {
        $data = $this->request('POST', '/recipe.json', array('name' => 'Test'));

        // Test content
        $recipe = array(
            'id'            => 3,
            'name'          => 'Test',
            'calories'      => 0,
            'proteins'      => 0,
            'carbohydrates' => 0,
            'lipids'        => 0,
            'foods'         => array()
        );

        $this->assertEquals($recipe, $data);

        // Check if the recipe was added to the list
        $recipes = array(
            array(
                'id'            => 1,
                'name'          => 'Crêpe',
                'calories'      => 342.15,
                'proteins'      => 28.22,
                'carbohydrates' => 27.27,
                'lipids'        => 12.98
            ),
            array(
                'id'            => 2,
                'name'          => 'Pancake au sarrasin',
                'calories'      => 966.40,
                'proteins'      => 45.60,
                'carbohydrates' => 146.00,
                'lipids'        => 20.50
            ),
            array(
                'id'            => 3,
                'name'          => 'Test',
                'calories'      => 0,
                'proteins'      => 0,
                'carbohydrates' => 0,
                'lipids'        => 0
            )
        );

        $this->checkList($recipes);
    }

    public function testGet()
    {
        $recipe = array(
            'id'            => 1,
            'name'          => 'Crêpe',
            'calories'      => 342.15,
            'proteins'      => 28.22,
            'carbohydrates' => 27.27,
            'lipids'        => 12.98,
            'foods'         => array(
                array(
                    'id'            => 1,
                    'name'          => 'Amandes',
                    'quantity'      => 25,
                    'calories'      => 93,
                    'proteins'      => 3.38,
                    'carbohydrates' => 14.68,
                    'lipids'        => 1.75,
                    'brand'         => null
                ),
                array(
                    'id'            => 2,
                    'name'          => 'Blanc de poulet',
                    'quantity'      => 4,
                    'calories'      => 26.8,
                    'proteins'      => 0.8,
                    'carbohydrates' => 0.36,
                    'lipids'        => 2.32,
                    'brand'         => 'Carrefour'
                )
            )
        );

        $data = $this->request('GET', '/recipe/1.json');

        $this->assertEquals($recipe, $data);
    }
}
