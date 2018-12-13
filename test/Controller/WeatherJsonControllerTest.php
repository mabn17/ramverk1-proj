<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class WeatherJsonControllerTest extends TestCase
{
    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new WeatherJsonController();
        $controller->setDI($di);
        $controller->initialize();


        $this->assertInstanceOf("\Anax\Controller\WeatherJsonController", $controller);
        $this->assertInstanceOf("\Anax\Weather\WeatherJsonModel", $controller->model);

        $responseR = $controller->indexAction();
        $this->assertEquals(gettype($responseR), 'object');

        $pos = "";
        $responseF = $controller->forecastActionGet($pos);
        $this->assertEquals($responseF, [["message" => "Could not find any data from $pos"]]);
    }

    public function testModelClass()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new WeatherJsonController();
        $controller->setDI($di);
        $controller->initialize();

        /* $this->assertEquals(gettype($controller->model->getAllData('karlskrona', 1)), 'array');
        $this->assertEquals(gettype($controller->model->getAllData('karlskrona', 0)), 'array'); */
    }
}
