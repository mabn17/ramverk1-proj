<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A controller that handles the IP Json responses
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class IpJsonController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var object $model initializes to the main model class
     */
    public $model;

    /**
     * Init method for /v-json route
     *
     * @return void
     */
    public function initialize()
    {
        $this->model = new \Anax\IpValidator\JsonValidatorModel;
    }

    /**
     * This is the index method action
     *
     * @return object the index page
     */
    public function indexAction()
    {

        // $path = $this->di->get("router")->getMatchedPath();
        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $ipB = ($session->has('ip')) ? $session->get("ip"): "Nothing is set";

        $page->add(
            "anax/v2/ip/explainJson",
            [
                "ip" => $ipB,
            ]
        );

        return $page->render([
            "title" => "Ip Json Validator",
        ]);
    }

    /**
     * Small sample of how to use get /look
     *
     * @return array information about the api
     */
    public function lookAction() : array
    {
        $json = [
            "Kmom01" => "Lägg in din valda ip adress efter validate/check?ip={ip adress}",
            "Kmom02" => "Lägg in din valda ip adress efter ip-json/map?ip={ip adress}",
        ];
        return [$json];
    }

    /**
     * Displays a JSON formated aswer for kmom01 (GET) /check
     *
     * @return array a message in json format about the given ip
     */
    public function checkActionGet() : array
    {
        $request = $this->di->get("request");
        $ipA = $request->getGet('ip') ?? "";
        $ipB = $this->model->validateKmomOne($ipA);

        $json = [
            "message" => "$ipB",
        ];
        return [$json];
    }

    /**
     * Displays a JSON formated aswer for kmom01 (POST) /check
     *
     * @return array same as checkActionGet but for post
     */
    public function checkActionPost() : array
    {
        $request = $this->di->get("request");
        $ipA = $request->getPost('ip') ?? "";
        $ipB = $this->model->validateKmomOne($ipA);

        $json = [
            "message" => "$ipB",
        ];
        return [$json];
    }

    /**
     * Displays a JSON formated aswer for kmom02 (GET) /map
     *
     * @return array extended information about the given ip adress
     */
    public function mapActionGet() : array
    {
        $request = $this->di->get("request");
        $ipA = $request->getGet('ip') ?? "";
        $apiRes = $this->model->validateKmomTwo($ipA);

        return [$apiRes];
    }

    /**
     * Displays a JSON formated aswer for kmom02 (POST) /map
     * Also hande one question from the test route (TODO: move this check)
     *
     * @return array extended information about the given ip adress
     */
    public function mapActionPost() : array
    {
        $request = $this->di->get("request");
        $ipA = $request->getPost('ip') ?? "";
        $kmomOne = $request->getPost('kmom01') ?? null;
        /* $apiRes = [];
        $json = []; */

        // Only for test route (Will change it soon)
        if ($kmomOne !== null) {
            $ipB = $this->model->validateKmomOne($ipA);
            $json = [
                "message" => "$ipB",
            ];
            return [$json];
        }

        $apiRes = $this->model->validateKmomTwo($ipA);
        return [$apiRes];
    }
}
