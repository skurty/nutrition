<?php

namespace Skurty\Tests\NutritionBundle\Controller;

use Skurty\Tests\NutritionBundle\Core\ControllerCoreTest;

class WeightControllerTest extends ControllerCoreTest
{
    protected $listUrl   = '/weights.json';
    protected $deleteUrl = '/weight/3.json';

    public function testList()
    {
        $weights = array(
            array(
                'id'     => 2,
                'date'   => '2014-08-05',
                'weight' => 74.8
            ),
            array(
                'id'     => 1,
                'date'   => '2014-07-06',
                'weight' => 72.8
            )
        );

        $this->checkList($weights);
    }

    public function testAdd()
    {
        $weight = array(
            'weight' => array(
                'date'   => '2014-08-11',
                'weight' => 75.4
            )
        );

        $data = $this->request('POST', '/weight.json', $weight);

        $this->assertEquals(1, $data);

        // Check if the recipe was added to the list
        $weights = array(
            array(
                'id'     => 3,
                'date'   => '2014-08-11',
                'weight' => 75.4
            ),
            array(
                'id'     => 2,
                'date'   => '2014-08-05',
                'weight' => 74.8
            ),
            array(
                'id'     => 1,
                'date'   => '2014-07-06',
                'weight' => 72.8
            )
        );

        $this->checkList($weights);
    }
}
