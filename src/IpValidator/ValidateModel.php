<?php

namespace Anax\IpValidator;

/**
 * Modell class to validate IP adresses route = /validate (FORM)
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class ValidateModel
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
        $this->apiKey = $prep["ip"] ?? "";
    }

    /**
     * IpValidator for kmom01
     *
     * @param string $ipA The ip adress
     *
     * @return array  If the adress is valid and its type.
     */
    public function validateKmomOne(string $ipA)
    {
        $res = "<span class='text-danger'> $ipA is not a valid IP.</span>";
        if (filter_var($ipA, FILTER_VALIDATE_IP)) {
            if (filter_var($ipA, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $res = $this->getIpVSixKmomOne($ipA);
            } else {
                $res = $this->getIpVFourKmomOne($ipA);
            }
        }

        return [
            "ipB" => $res,
            "apiRes" => "",
        ];
    }

    /**
     * IpValidator for kmom02
     *
     * @param string $ipA The ip adress
     *
     * @return array $res Information about the ip adress
     */
    public function validateKmomTwo(string $ipA) : array
    {
        $res = [
            "apiRes" => null,
            "ipB" => "<span class='text-danger'> $ipA is not a valid IP.</span>"
        ];

        if (filter_var($ipA, FILTER_VALIDATE_IP)) {
            if (filter_var($ipA, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $res = $this->getIpVSixKmomTwo($ipA);
            } else {
                $res = $this->getIpVFourKmomTwo($ipA);
            }
        }
        return $res;
    }

    /**
     * Curls http://api.ipstack.com with the given ip adress
     *
     * @param string $ipA The ip adress
     *
     * @return array Information about the ip adress
     */
    public function getJson(string $ipA) : array
    {
        /* $filename = __DIR__ . "/key.txt";
        $accessKey = file($filename)[0]; */
        $accessKey = $this->apiKey;

        $chA = curl_init('http://api.ipstack.com/'.$ipA.'?access_key='.$accessKey.'');
        curl_setopt($chA, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($chA);
        curl_close($chA);
        return json_decode($json, true);
    }

    /**
     * Gives a response that the ip adress is IPv6
     *
     * @param string $ipA The ip adress
     *
     * @return string is valid and type IPv6
     */
    public function getIpVSixKmomOne(string $ipA) : string
    {
        return "<span class='text-success'> $ipA is a valid IPv6 adress.</span>";
    }

    /**
     * Generates a responce for IPv6 kmom02
     *
     * @param string $ipA The ip adress
     *
     * @return array  information about the ip adress
     */
    public function getIpVSixKmomTwo(string $ipA) : array
    {
        $ipB = "<span class='text-success'> $ipA is a valid IPv6 adress.</span>";
        $apiRes = $this->getJson($ipA);

        return [
            "apiRes" => $apiRes,
            "ipB" => $ipB
        ];
    }

    /**
     * Gives a response that the ip adress is IPv6
     *
     * @param string $ipA The ip adress
     *
     * @return string  is valid and type IPv6
     */
    public function getIpVFourKmomOne(string $ipA) : string
    {
        return "<span class='text-success'> $ipA is a valid IPv4 adress.</span>";
    }

    /**
     * Generates a responce for OPv4 kmom02
     *
     * @param string $ipA The ip adress
     *
     * @return array information about the ip adress
     */
    public function getIpVFourKmomTwo(string $ipA) : array
    {
        $ipB = "<span class='text-success'> $ipA is a valid IPv4 adress.</span>";
        $apiRes = $this->getJson($ipA);

        return [
            "apiRes" => $apiRes,
            "ipB" => $ipB
        ];
    }

    /**
     * Checks the post values (Mainly for a test route)
     *
     * @param string|bool|null $kmomOne submit value
     * @param string|bool|null $kmomTwo submit value
     * @param string $ipA The ip adress
     *
     * @return array|string with the response
     */
    public function checkPost($kmomOne, $kmomTwo, $ipA) : array
    {
        if ($kmomTwo !== null) {
            return $this->validateKmomTwo($ipA);
        } elseif ($kmomOne !== null) {
            return $this->validateKmomOne($ipA);
        }

        return [
            "ipB" => "",
            "apiRes" => "",
        ];
    }
}
