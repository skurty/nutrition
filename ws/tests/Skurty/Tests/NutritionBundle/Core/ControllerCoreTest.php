<?php

namespace Skurty\Tests\NutritionBundle\Core;

use Silex\WebTestCase;

class ControllerCoreTest extends WebTestCase
{
    protected $root = '/ws';
    protected $listUrl;
    protected $deleteUrl;

    public function createApplication()
    {
        return require $_SERVER['APP_DIR'] . '/app_test.php';
    }

    /**
     * Launch a request and get the data in json
     * @param  [type] $method [description]
     * @param  [type] $url    [description]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    protected function request($method, $url, $params = array(), $jsonDecode = true)
    {
        $client = $this->createClient();

        if ($method == 'POST' || $method == 'PUT') {
            $client->request($method, $this->root . $url, array(), array(), array(), json_encode($params));
        } else {
            $client->request($method, $this->root . $url);
        }

        $response = $client->getResponse();

        $this->assertTrue($response->isOk());

        if ($jsonDecode) {
            return json_decode($response->getContent(), true);
        } else {
            return $response->getContent();
        }
    }

    protected function checkList($brands)
    {
        $data = $this->request('GET', $this->listUrl);

        // Test content
        $this->assertEquals($brands, $data);
    }

    public function testDelete()
    {
        $content = $this->request('DELETE', $this->deleteUrl, array(), false);

        $this->assertEquals($content, 1);

        $this->testList();
    }
}
