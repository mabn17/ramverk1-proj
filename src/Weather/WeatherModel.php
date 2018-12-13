<?php

namespace Anax\Weather;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * Model class witch main responsability is handeling data for /vader
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherModel
{
    /**
     * @var string $apiKey initializes the key
     */
    protected $apiKey;

    /**
     * Sets the api key.
     *
     * @return void
     */
    public function __construct()
    {
        $prep = require ANAX_INSTALL_PATH . "/config/keys.php";
        $this->apiKey = $prep["darksky"];
    }

    /**
     * Gets the weekly weather.
     *
     * @param array $coords contains the values longitude and latitude.
     *
     * @return array with the weekly weather data.
     */
    public function getData(array $coords) : array
    {
        if (!isset($coords[0]['lat']) || !isset($coords[0]['lon'])) {
            return [];
        }

        $accessKey = $this->apiKey;
        $location = $coords[0]['lat'] . ',' . $coords[0]['lon'];

        $chA = curl_init(
            'https://api.darksky.net/forecast/'.
            $accessKey . '/' . $location.
            '?exclude=minutely,hourly,currently,alerts,flags&extend=daily&lang=sv&units=auto'
        );
        curl_setopt($chA, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($chA);
        curl_close($chA);

        $apiRes = json_decode($json, true);
        return [$apiRes];
    }

    /**
     * Gets todays date and subtract the number of days sent in
     *
     * @param string|int $nrOfDays the number of days
     *
     * @return string the date in unix format
     */
    public function getDate($nrOfDays) : string
    {
        $myDate = new \Datetime();
        $myDate->sub(new \DateInterval('P'. (intval($nrOfDays) + 1) .'D'));
        return $myDate->format('U');
    }

    /**
     * Takes a location and turns it into coordinates.
     *
     * @param string $adrs the location, can be anything like Zip, adress and so on
     *
     * @return array with longitude and latitude values NOTE: will be empty if nothing is found
     */
    public function geocode(string $adrs) : array
    {
        $city = urlencode($adrs);

        // Just a random email query (not returning anything wihtout it ??)
        $url = "https://nominatim.openstreetmap.org/?format=json&addressdetails=1&q={$city}&limit=1&email=aneamil@live.se";
        
        $chA = curl_init($url);
        curl_setopt($chA, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($chA);
        curl_close($chA);

        $apiRes = json_decode($json, true) ?? [];
        return $apiRes;
    }

    /**
     * Preforms a multi curl to get the previus weather
     *
     * @param string|int    $nrOfDays amount of previus days
     * @param string        $adrs the position
     *
     * @return array with all the weather details for each day
     */
    public function multiCurl($nrOfDays, string $adrs) : array
    {
        $urls = $this->getUrls($nrOfDays, $adrs);

        if ($urls == [[]] || $urls == []) {
            return [[]];
        }

        $multi = curl_multi_init();
        $handles = [];
        $htmls = [];

        foreach ($urls as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_multi_add_handle($multi, $ch);
            $handles[$url] = $ch;
        }

        $this->startMultiCurl($multi);

        foreach ($handles as $channel) {
            $html = curl_multi_getcontent($channel);
            $htmls[] = json_decode($html, true);
            curl_multi_remove_handle($multi, $channel);
        }

        curl_multi_close($multi);

        return $htmls;
    }

    /**
     * Continueation for $this->multiCurl
     */
    public function startMultiCurl($multi)
    {
        do {
            $mrc = curl_multi_exec($multi, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($multi) == -1) {
                usleep(100);
            }
            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    /**
     * Returns the api key for DarkS.N
     *
     * @return string api key
     */
    private function getKey() : string
    {
        return $this->apiKey;
    }

    /**
     * Returns a list of urls
     *
     * @param string|int $nrOfDays the total amount of days (MAX 30)
     * @param string $adrs the given adress/location
     *
     * @return array a list of urls to curl
     */
    private function getUrls($nrOfDays, string $adrs) : array
    {
        $coords = $this->geocode($adrs);
        $nrOfDays = ($nrOfDays > 30) ? 30 : $nrOfDays;

        if ($coords == []) {
            return [[]];
        }

        $urls = [];
        $accessKey = $this->getKey();
        $location = $coords[0]['lat'] . ',' . $coords[0]['lon'];

        for ($i = 0; $i < $nrOfDays; $i++) {
            $time = $this->getDate("$i");
            $urls[] = 'https://api.darksky.net/forecast/'.$accessKey.'/'.$location.','.$time.'?exclude=minutely,hourly,currently,alerts,flags&extend=daily&lang=sv&units=auto';
        }

        return $urls;
    }
    /**
     * Method to test dipendency injection
     * Might aswell be ignored
     *
     * @return string the title for a view
     */
    public function hello() : string
    {
        return 'Väder app (Test - Taget från $di->get("weather"))';
    }
}
