<?php

namespace Skurty\Tests\NutritionBundle\Controller;

use Skurty\Tests\NutritionBundle\Core\ControllerCoreTest;

class BrandControllerTest extends ControllerCoreTest
{
    protected $listUrl   = '/brands.json';
    protected $deleteUrl = '/brand/4.json';

    public function testList()
    {
        $brands = array(
            array('id' => 1, 'name' => 'Carrefour'),
            array('id' => 2, 'name' => 'D\'aucy'),
            array('id' => 3, 'name' => 'Panzani')
        );

        $this->checkList($brands);
    }

    public function testAdd()
    {
        $data = $this->request('POST', '/brand.json', array('name' => 'Auchan'));

        // Test content
        $this->assertEquals(1, $data);

        // Check if brand added to the list
        $brands = array(
            array('id' => 1, 'name' => 'Carrefour'),
            array('id' => 2, 'name' => 'D\'aucy'),
            array('id' => 3, 'name' => 'Panzani'),
            array('id' => 4, 'name' => 'Auchan')
        );

        $this->checkList($brands);
    }

    public function testAddExistingBrand()
    {
        $data = $this->request('POST', '/brand.json', array('name' => 'Auchan'));
        
        $this->assertEquals(array('error' => 'Brand already exists'), $data);
    }

    public function testEdit()
    {
        $data = $this->request('PUT', '/brand/4.json', array('name' => 'Apple'));

        // Test content
        $this->assertEquals(1, $data);

        // Check if the brand was edited
        $brands = array(
            array('id' => 1, 'name' => 'Carrefour'),
            array('id' => 2, 'name' => 'D\'aucy'),
            array('id' => 3, 'name' => 'Panzani'),
            array('id' => 4, 'name' => 'Apple')
        );

        $this->checkList($brands);
    }

    public function testEditDuplicateName()
    {
        $data = $this->request('PUT', '/brand/1.json', array('name' => 'Apple'));

        $this->assertEquals(array('error' => 'A brand with the same name already exists'), $data);
    }

    public function testDeleteIncorrectId()
    {
        $data = $this->request('DELETE', '/brand/200.json');

        $this->assertEquals(array('error' => 'Incorrect ID'), $data);
    }
}
