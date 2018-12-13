<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A controller for the Weather route.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherController implements ContainerInjectableInterface
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
        $this->model = new \Anax\Weather\WeatherModel;
    }

    /**
     * Front page for the weather application
     *
     * @return object the index page
     */
    public function indexAction()
    {
        $session = $this->di->get("session");
        $page = $this->di->get("page");
        $apiRes = ($session->has('apiRes')) ? $session->get("apiRes") : "";
        $weather = $this->di->get("weather");
        $test = $session->get('test');
        $page->add(
            "anax/v2/weather/index",
            [
                "jsonData" => $session->get('jsonData'),
                "locationData" => $session->get('locationData'),
                "weather" => $weather->hello(),
                "apiRes" => $apiRes,
                "search" => $session->get('search'),
                "test" => $test,
            ]
        );

        return $page->render([
            "title" => "Väder app",
        ]);
    }

    /**
     * Handles and parses Post data then redirect the user back to the routes
     * index page.
     *
     * @return object redirect to the routes index.
     */
    public function locationActionPost()
    {
        $search = $this->di->get("request")->getPost('location') ?? "";
        $location = $this->di->get("weather")->geocode($search);
        $jsonData = $this->model->getData($location);
        $test = $this->model->multiCurl(30, $search);
        
        $this->di->get("session")->set('jsonData', $jsonData);
        $this->di->get("session")->set('locationData', $location);
        $this->di->get("session")->set('search', $search);
        $this->di->get("session")->set('test', $test);

        return $this->di->get("response")->redirect("vader");
    }
}
