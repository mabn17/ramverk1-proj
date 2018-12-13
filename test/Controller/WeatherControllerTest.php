<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class WeatherControllerTest extends TestCase
{
    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();


        $this->assertInstanceOf("\Anax\Controller\WeatherController", $controller);
        $this->assertInstanceOf("\Anax\Weather\WeatherModel", $controller->model);

        /* $responseR = $controller->indexAction();
        $this->assertEquals(gettype($responseR), 'object');

        $responseP = $controller->locationActionPost();
        $this->assertEquals(gettype($responseP), 'object');

        $this->assertEquals(gettype($controller->model->getDate(1)), 'string'); */
    }
}
