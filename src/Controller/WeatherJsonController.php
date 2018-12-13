<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A controller for the Weather Json route.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherJsonController implements ContainerInjectableInterface
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
        $this->model = new \Anax\Weather\WeatherJsonModel;
    }

    /**
     * Front page for the weather application
     *
     * @return object index page
     */
    public function indexAction()
    {
        $page = $this->di->get("page");
        $page->add(
            "anax/v2/weather/explainJson",
            [
                "title" => "Väder app",
            ]
        );

        return $page->render([
            "title" => "Väder app",
        ]);
    }

    /**
     * Returns a Json response with the weather data.
     *
     * @param string $pos forecase/[pos], can be zip, adress and so on.
     *
     * @return array with the weather data.
     */
    public function forecastActionGet(string $pos = "") : array
    {
        $request = $this->di->get("request");
        $days = $request->getGet('days') ?? "30";
        $apiRes = $this->model->getAllData($pos, $days);

        return [$apiRes];
    }
}
