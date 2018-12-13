<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class IpJsonControllerTest extends TestCase
{
    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new IpJsonController();
        $controller->setDI($di);
        $controller->initialize();

        $this->assertInstanceOf("\Anax\Controller\IpJsonController", $controller);

        $res = $controller->indexAction();
        $this->assertEquals(gettype($res), 'object');
    }

    /**
     * Test the route "/look"
     */
    public function testLookAction()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new IpJsonController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->lookAction();

        $this->assertEquals($res[0]["Kmom01"], "Lägg in din valda ip adress efter validate/check?ip={ip adress}");
        $this->assertEquals($res[0]["Kmom02"], "Lägg in din valda ip adress efter ip-json/map?ip={ip adress}");
    }

    public function testCheckActionGet()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new IpJsonController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->checkActionGet();

        $this->assertEquals("array", gettype($res));
    }

    public function testCheckActionPost()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new IpJsonController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->checkActionPost();

        $this->assertEquals("array", gettype($res));
    }

    public function testMapActionGet()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new IpJsonController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->mapActionGet();

        $this->assertEquals("array", gettype($res));
    }

    public function testMapActionPost()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new IpJsonController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->mapActionPost();

        $this->assertEquals("array", gettype($res));
    }

    /* public function testSubModel()
    {
        $model = new \Anax\IpValidator\JsonValidatorModel;
        $adrs = "127.0.0.1";
        $adrsE = "$adrs is a valid IPv4 adress.";
        $adrsT = "2001:0db8:85a3:0000:0000:8a2e:0370:7334";
        $adrsTE = "$adrsT is a valid IPv6 adress.";
        $iFourMsg = $model->validateKmomOne($adrs);
        $iSixMsg = $model->validateKmomOne($adrsT);

        $this->assertEquals($iFourMsg, $adrsE);
        $this->assertEquals($iSixMsg, $adrsTE);

        $iFourMsg = $model->getIpVFourKmomOne($adrs);
        $iSixMsg = $model->getIpVSixKmomOne($adrsT);

        $this->assertEquals($iFourMsg, $adrsE);
        $this->assertEquals($iSixMsg, $adrsTE);

        $jsTwo = $model->validateKmomTwo($adrs);
        $this->assertEquals(gettype($jsTwo), "array");
    } */
}
