<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class IpControllerTest extends TestCase
{
    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $this->assertInstanceOf("\Anax\Controller\IpController", $controller);

        $responseR = $controller->indexAction();
        $this->assertEquals(gettype($responseR), 'object');
    }

    public function testUpdateActionPost()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . '/config/di');
        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $responseR = $controller->updateActionPost();

        $this->assertEquals($di->get("response")->redirect("validate"), $responseR);
    }

    /* public function testSubModel()
    {
        $model = new \Anax\IpValidator\ValidateModel;
        $adrs = "127.0.0.1";
        $adrsE = "<span class='text-success'> $adrs is a valid IPv4 adress.</span>";
        $adrsT = "2001:0db8:85a3:0000:0000:8a2e:0370:7334";
        $adrsTE = "<span class='text-success'> $adrsT is a valid IPv6 adress.</span>";
        $failedMsg = "<span class='text-danger'> 123 is not a valid IP.</span>";

        $iSixString = $model->getIpVSixKmomOne($adrsT);
        $iFourString = $model->getIpVFourKmomOne($adrs);

        $this->assertEquals($adrsTE, $iSixString);
        $this->assertEquals($adrsE, $iFourString);

        $arr = $model->validateKmomOne($adrs);
        $this->assertEquals(sizeof($arr), 2);
        $this->assertEquals($model->validateKmomOne("123")["ipB"], $failedMsg);

        $this->assertEquals($model->validateKmomTwo("123")["ipB"], "<span class='text-danger'> 123 is not a valid IP.</span>");

        $kmomSixTwo = $model->getIpVSixKmomTwo($adrs);
        $this->assertEquals(gettype($kmomSixTwo), "array");

        $oneMsg = $model->validateKmomOne($adrsT);

        $this->assertEquals(gettype($oneMsg), "array");
        $this->assertEquals($model->checkPost(null, " ", $adrs), $model->validateKmomTwo($adrs));
        $this->assertEquals($model->checkPost(" ", null, $adrs), $model->validateKmomOne($adrs));

        $this->assertEquals(gettype($model->validateKmomTwo($adrsT)), "array");
    } */
}
