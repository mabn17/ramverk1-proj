<?php

namespace Anax\Weather;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * Model class witch main responsability is handeling Json data for /v-json
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherJsonModel
{
    /**
     * Used by controller returns different values dippending on the given data.
     *
     * @param string $pos the given location like a ZIP or adress and so on.
     * @param string|int $days the number of previus days
     *
     * @return array with the weather data.
     */
    public function getAllData(string $pos, $days) : array
    {
        $position = urlencode($pos);
        if ($pos == "") {
            return ["message" => "Could not find any data from $pos"];
        } elseif ($days == 0) {
            return $this->getCurrentOnly($position);
        }

        return $this->getAll($position, $days);
    }

    /**
     * Only returns the weekly data.
     *
     * @param string $pos the wanted position like a ZIP, adress and so on.
     *
     * @return array the weekly weather data.
     */
    public function getCurrentOnly(string $pos) : array
    {
        $wModel = new WeatherModel;
        $location = $wModel->geocode($pos);
        $res = $wModel->getData($location)[0];

        return [
            "current" => $res,
        ];
    }

    /**
     * Gets the weather data from previus days.
     *
     * @param string $pos the wanted position like a ZIP, adress and so on.
     * @param string|int $days the number of previus days
     *
     * @return array the weather data for the days.
     */
    public function getPrevDays(string $pos, $days) : array
    {
        $wModel = new WeatherModel;
        $res = $wModel->multiCurl($days, $pos);
        $lastRes = [];

        foreach ($res as $day) {
            $lastRes[] = $day["daily"]["data"];
        }

        return $lastRes;
    }

    /**
     * Combines both this->getCurrentOnly and this->getPrevDays.
     *
     * @param string $position the wanted position like a ZIP, adress and so on.
     * @param string|int $days the number of previus days.
     *
     * @return array with the combined data.
     */
    public function getAll(string $position, $days)
    {
        $res = [];
        $res["current"] = $this->getCurrentOnly($position)["current"];
        $res["previous"] = $this->getPrevDays($position, $days);

        return [
            "current" => $res["current"],
            "previous" => $res["previous"]
        ];
    }
}
