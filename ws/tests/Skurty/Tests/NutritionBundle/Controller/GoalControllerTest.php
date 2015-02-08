<?php

namespace Skurty\Tests\NutritionBundle\Controller;

use Skurty\Tests\NutritionBundle\Core\ControllerCoreTest;

class WoalControllerTest extends ControllerCoreTest
{
    protected $listUrl   = '/goals.json';
    protected $deleteUrl = '/goal/3.json';

    public function testList()
    {
        $goals = array(
            array(
                'id'            => 2,
                'date'          => '2014-01-27',
                'calories'      => 3800,
                'proteins'      => 245,
                'carbohydrates' => 476,
                'lipids'        => 100
            ),
            array(
                'id'            => 1,
                'date'          => '2014-01-13',
                'calories'      => 3375,
                'proteins'      => 250,
                'carbohydrates' => 408,
                'lipids'        => 81
            )
        );

        $this->checkList($goals);
    }

    public function testAdd()
    {
        $goal = array(
            'goal' => array(
                'date'          => '2014-08-11',
                'calories'      => 1800,
                'proteins'      => 180,
                'carbohydrates' => 100,
                'lipids'        => 75
            )
        );

        $data = $this->request('POST', '/goal.json', $goal);

        $this->assertEquals(1, $data);

        // Check if the recipe was added to the list
        $goals = array(
            array(
                'id'            => 3,
                'date'          => '2014-08-11',
                'calories'      => 1800,
                'proteins'      => 180,
                'carbohydrates' => 100,
                'lipids'        => 75
            ),
            array(
                'id'            => 2,
                'date'          => '2014-01-27',
                'calories'      => 3800,
                'proteins'      => 245,
                'carbohydrates' => 476,
                'lipids'        => 100
            ),
            array(
                'id'            => 1,
                'date'          => '2014-01-13',
                'calories'      => 3375,
                'proteins'      => 250,
                'carbohydrates' => 408,
                'lipids'        => 81
            )
        );

        $this->checkList($goals);
    }
}
