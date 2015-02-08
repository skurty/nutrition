<?php

namespace Skurty\Tests\NutritionBundle\Controller;

use Skurty\Tests\NutritionBundle\Core\ControllerCoreTest;

class StatisticControllerTest extends ControllerCoreTest
{
    protected $listUrl   = '/statistics.json';

    public function testList()
    {
        $statistics = array(
            'calories' => array(
                '09/08/2014' => 1912,
                '10/08/2014' => 2012
            ),
            'weights' => array(
                '2014-07-06' => 72.8,
                '2014-08-05' => 74.8
            ),
            'startDate' => '2013-08-01',
            'endDate'   => '2014-08-10',
            'macronutrients' => array(
                '09/08/2014' => array(
                    'proteins'      => 186,
                    'carbohydrates' => 91,
                    'lipids'        => 84
                ),
                '10/08/2014' => array(
                    'proteins'      => 189,
                    'carbohydrates' => 97,
                    'lipids'        => 92
                )
            )
        );

        $this->checkList($statistics);
    }

    public function testDelete()
    {
    }
}
