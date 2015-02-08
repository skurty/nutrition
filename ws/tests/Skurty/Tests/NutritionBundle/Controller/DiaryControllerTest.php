<?php

namespace Skurty\Tests\NutritionBundle\Controller;

use Skurty\Tests\NutritionBundle\Core\ControllerCoreTest;

class DiaryControllerTest extends ControllerCoreTest
{
    protected $listUrl = '/diaries.json';

    public function testList()
    {
        $diaries = array(
            'diaries' => array(
                1 => array(
                    array(
                        'id'            => 1,
                        'name'          => 'Amandes',
                        'quantity'      => 100,
                        'unit'          => 'g',
                        'calories'      => 578,
                        'proteins'      => 19,
                        'carbohydrates' => 4,
                        'lipids'        => 54,
                        'brand_name'    => null
                    )
                ),
                2 => array(
                    array(
                        'id'            => 2,
                        'name'          => 'Blanc de poulet',
                        'quantity'      => 1,
                        'unit'          => 'tranche',
                        'calories'      => 32,
                        'proteins'      => 6.3,
                        'carbohydrates' => 0.3,
                        'lipids'        => 0.6,
                        'brand_name'    => 'Carrefour'
                    )
                ),
                3 => array(),
                4 => array(),
                5 => array(),
                6 => array()
            ),
            'totalMeals' => array(
                1 => array(
                    'calories'      => 578,
                    'proteins'      => 19,
                    'carbohydrates' => 4,
                    'lipids'        => 54
                ),
                2 => array(
                    'calories'      => 32,
                    'proteins'      => 6,
                    'carbohydrates' => 0,
                    'lipids'        => 1
                )
            ),
            'total' => array(
                'calories'      => 610,
                'proteins'      => 25,
                'carbohydrates' => 4,
                'lipids'        => 55
            ),
            'goal' => array(
                'calories'      => 3800,
                'proteins'      => 245,
                'carbohydrates' => 476,
                'lipids'        => 100
            )
        );

        $this->checkList($diaries);
    }

    public function testAddWithFood()
    {
        $diary = array(
            'food'          => 1,
            'quantity'      => 10,
            'meal'          => 3,
            'date'          => '2014-08-11'
        );

        $data = $this->request('POST', '/diary.json', $diary);

        // Test content
        $result = array(
            'diary' => array(
                'name'          => 'Amandes',
                'quantity'      => 10,
                'unit'          => 'g',
                'calories'      => 57.8,
                'proteins'      => 1.9,
                'carbohydrates' => 0.4,
                'lipids'        => 5.4,
                'brand_id'      => null,
                'date'          => '2014-08-11',
                'meal_id'       => 3,
                'food_id'       => 1,
                'id'            => 3
            ),
            'totalMeal' => array(
                'calories'      => 58,
                'proteins'      => 2,
                'carbohydrates' => 0,
                'lipids'        => 5
            ),
            'total' => array(
                'calories'      => 668,
                'proteins'      => 27,
                'carbohydrates' => 5,
                'lipids'        => 60
            )
        );

        $this->assertEquals($result, $data);

        $diaries = array(
            'diaries' => array(
                1 => array(
                    array(
                        'id'            => 1,
                        'name'          => 'Amandes',
                        'quantity'      => 100,
                        'unit'          => 'g',
                        'calories'      => 578,
                        'proteins'      => 19,
                        'carbohydrates' => 4,
                        'lipids'        => 54,
                        'brand_name'    => null
                    )
                ),
                2 => array(
                    array(
                        'id'            => 2,
                        'name'          => 'Blanc de poulet',
                        'quantity'      => 1,
                        'unit'          => 'tranche',
                        'calories'      => 32,
                        'proteins'      => 6.3,
                        'carbohydrates' => 0.3,
                        'lipids'        => 0.6,
                        'brand_name'    => 'Carrefour'
                    )
                ),
                3 => array(
                    array(
                        'id'            => 3,
                        'name'          => 'Amandes',
                        'quantity'      => 10,
                        'unit'          => 'g',
                        'calories'      => 57.8,
                        'proteins'      => 1.9,
                        'carbohydrates' => 0.4,
                        'lipids'        => 5.4,
                        'brand_name'    => null
                    )
                ),
                4 => array(),
                5 => array(),
                6 => array()
            ),
            'totalMeals' => array(
                1 => array(
                    'calories'      => 578,
                    'proteins'      => 19,
                    'carbohydrates' => 4,
                    'lipids'        => 54
                ),
                2 => array(
                    'calories'      => 32,
                    'proteins'      => 6,
                    'carbohydrates' => 0,
                    'lipids'        => 1
                ),
                3 => array(
                    'calories'      => 58,
                    'proteins'      => 2,
                    'carbohydrates' => 0,
                    'lipids'        => 5
                )
            ),
            'total' => array(
                'calories'      => 668,
                'proteins'      => 27,
                'carbohydrates' => 5,
                'lipids'        => 60
            ),
            'goal' => array(
                'calories'      => 3800,
                'proteins'      => 245,
                'carbohydrates' => 476,
                'lipids'        => 100
            )
        );

        $this->checkList($diaries);
    }

    public function testEdit()
    {
        $data = $this->request('PUT', '/diary/3.json', array('quantity' => 5));

        $expected = array(
            'diary' => array(
                'quantity'      => 5,
                'calories'      => 28.9,
                'proteins'      => 0.95,
                'carbohydrates' => 0.2,
                'lipids'        => 2.7
            ),
            'totalMeal' => array(
                'calories'      => 29,
                'proteins'      => 1,
                'carbohydrates' => 0,
                'lipids'        => 3
            ),
            'total' => array(
                'calories'      => 639,
                'proteins'      => 26,
                'carbohydrates' => 5,
                'lipids'        => 57
            )
        );

        $this->assertEquals($expected, $data);

        $diaries = array(
            'diaries' => array(
                1 => array(
                    array(
                        'id'            => 1,
                        'name'          => 'Amandes',
                        'quantity'      => 100,
                        'unit'          => 'g',
                        'calories'      => 578,
                        'proteins'      => 19,
                        'carbohydrates' => 4,
                        'lipids'        => 54,
                        'brand_name'    => null
                    )
                ),
                2 => array(
                    array(
                        'id'            => 2,
                        'name'          => 'Blanc de poulet',
                        'quantity'      => 1,
                        'unit'          => 'tranche',
                        'calories'      => 32,
                        'proteins'      => 6.3,
                        'carbohydrates' => 0.3,
                        'lipids'        => 0.6,
                        'brand_name'    => 'Carrefour'
                    )
                ),
                3 => array(
                    array(
                        'id'            => 3,
                        'name'          => 'Amandes',
                        'quantity'      => 5,
                        'unit'          => 'g',
                        'calories'      => 28.9,
                        'proteins'      => 0.95,
                        'carbohydrates' => 0.2,
                        'lipids'        => 2.7,
                        'brand_name'    => null
                    )
                ),
                4 => array(),
                5 => array(),
                6 => array()
            ),
            'totalMeals' => array(
                1 => array(
                    'calories'      => 578,
                    'proteins'      => 19,
                    'carbohydrates' => 4,
                    'lipids'        => 54
                ),
                2 => array(
                    'calories'      => 32,
                    'proteins'      => 6,
                    'carbohydrates' => 0,
                    'lipids'        => 1
                ),
                3 => array(
                    'calories'      => 29,
                    'proteins'      => 1,
                    'carbohydrates' => 0,
                    'lipids'        => 3
                )
            ),
            'total' => array(
                'calories'      => 639,
                'proteins'      => 26,
                'carbohydrates' => 5,
                'lipids'        => 57
            ),
            'goal' => array(
                'calories'      => 3800,
                'proteins'      => 245,
                'carbohydrates' => 476,
                'lipids'        => 100
            )
        );

        $this->checkList($diaries);
    }

    public function testDelete()
    {
        $data = $this->request('DELETE', '/diary/3.json');

        $expected = array(
            'totalMeal' => array(
                'calories'      => 0,
                'proteins'      => 0,
                'carbohydrates' => 0,
                'lipids'        => 0
            ),
            'total' => array(
                'calories'      => 610,
                'proteins'      => 25,
                'carbohydrates' => 4,
                'lipids'        => 55
            )
        );

        $this->assertEquals($expected, $data);

        $this->testList();
    }

    public function testCopy()
    {
        $content = $this->request('POST', '/diary/1/copy.json', array('meal' => 4), false);

        $this->assertEquals($content, 1);

        $diaries = array(
            'diaries' => array(
                1 => array(
                    array(
                        'id'            => 1,
                        'name'          => 'Amandes',
                        'quantity'      => 100,
                        'unit'          => 'g',
                        'calories'      => 578,
                        'proteins'      => 19,
                        'carbohydrates' => 4,
                        'lipids'        => 54,
                        'brand_name'    => null
                    )
                ),
                2 => array(
                    array(
                        'id'            => 2,
                        'name'          => 'Blanc de poulet',
                        'quantity'      => 1,
                        'unit'          => 'tranche',
                        'calories'      => 32,
                        'proteins'      => 6.3,
                        'carbohydrates' => 0.3,
                        'lipids'        => 0.6,
                        'brand_name'    => 'Carrefour'
                    )
                ),
                3 => array(),
                4 => array(
                    array(
                        'id'            => 4,
                        'name'          => 'Amandes',
                        'quantity'      => 100,
                        'unit'          => 'g',
                        'calories'      => 578,
                        'proteins'      => 19,
                        'carbohydrates' => 4,
                        'lipids'        => 54,
                        'brand_name'    => null
                    )
                ),
                5 => array(),
                6 => array()
            ),
            'totalMeals' => array(
                1 => array(
                    'calories'      => 578,
                    'proteins'      => 19,
                    'carbohydrates' => 4,
                    'lipids'        => 54
                ),
                2 => array(
                    'calories'      => 32,
                    'proteins'      => 6,
                    'carbohydrates' => 0,
                    'lipids'        => 1
                ),
                4 => array(
                    'calories'      => 578,
                    'proteins'      => 19,
                    'carbohydrates' => 4,
                    'lipids'        => 54
                )
            ),
            'total' => array(
                'calories'      => 1188,
                'proteins'      => 44,
                'carbohydrates' => 8,
                'lipids'        => 109
            ),
            'goal' => array(
                'calories'      => 3800,
                'proteins'      => 245,
                'carbohydrates' => 476,
                'lipids'        => 100
            )
        );

        $this->checkList($diaries);
    }

    public function testMove()
    {
        $content = $this->request('PUT', '/diary/1/move.json', array('meal' => 5), false);

        $this->assertEquals($content, 1);

        $diaries = array(
            'diaries' => array(
                1 => array(),
                2 => array(
                    array(
                        'id'            => 2,
                        'name'          => 'Blanc de poulet',
                        'quantity'      => 1,
                        'unit'          => 'tranche',
                        'calories'      => 32,
                        'proteins'      => 6.3,
                        'carbohydrates' => 0.3,
                        'lipids'        => 0.6,
                        'brand_name'    => 'Carrefour'
                    )
                ),
                3 => array(),
                4 => array(
                    array(
                        'id'            => 4,
                        'name'          => 'Amandes',
                        'quantity'      => 100,
                        'unit'          => 'g',
                        'calories'      => 578,
                        'proteins'      => 19,
                        'carbohydrates' => 4,
                        'lipids'        => 54,
                        'brand_name'    => null
                    )
                ),
                5 => array(
                    array(
                        'id'            => 1,
                        'name'          => 'Amandes',
                        'quantity'      => 100,
                        'unit'          => 'g',
                        'calories'      => 578,
                        'proteins'      => 19,
                        'carbohydrates' => 4,
                        'lipids'        => 54,
                        'brand_name'    => null
                    )
                ),
                6 => array()
            ),
            'totalMeals' => array(
                2 => array(
                    'calories'      => 32,
                    'proteins'      => 6,
                    'carbohydrates' => 0,
                    'lipids'        => 1
                ),
                4 => array(
                    'calories'      => 578,
                    'proteins'      => 19,
                    'carbohydrates' => 4,
                    'lipids'        => 54
                ),
                5 => array(
                    'calories'      => 578,
                    'proteins'      => 19,
                    'carbohydrates' => 4,
                    'lipids'        => 54
                )
            ),
            'total' => array(
                'calories'      => 1188,
                'proteins'      => 44,
                'carbohydrates' => 8,
                'lipids'        => 109
            ),
            'goal' => array(
                'calories'      => 3800,
                'proteins'      => 245,
                'carbohydrates' => 476,
                'lipids'        => 100
            )
        );

        $this->checkList($diaries);
    }
}
