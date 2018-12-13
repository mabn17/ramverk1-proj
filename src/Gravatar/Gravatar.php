<?php

namespace Anax\Gravatar;

/**
 * Class that returns a gravatar from a given email adress.
 */
class Gravatar
{
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @source https://gravatar.com/site/implement/images/php/
     *
     * @param string $email The email address
     * @param string $size Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $default Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boolean $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     *
     * @return String containing either just a URL or a complete image tag
     */
    public function getGravatar($email, $size = 80, $default = 'mp', $rating = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$size&d=$default&r=$rating";

        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
}
