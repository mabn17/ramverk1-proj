<?php

namespace Anax\MdFilter;

/* use Michelf\MarkDownExtra; */
// Dosent work on student server with use? - No idea why
require_once ANAX_INSTALL_PATH . '/vendor/michelf/php-markdown/Michelf/Markdown.inc.php';

/**
 * Filter and format text content.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.UnusedPrivateField)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class MdFilter
{
    /**
     * @var array $filters Supported filters with method names of
     *                     their respective handler.
     */
    private $filters = [
        "markdown"  => "markdown",
    ];


    /**
     * Call each filter on the text and return the processed text.
     *
     * @param string $text      The text to filter.
     * @param array $filters    Array of filters to use.
     *
     * @return string with the formatted text.
     */
    public function parse($text, $filter = "markdown") : string
    {
        $filterArray = explode(",", $filter);
        foreach ($filterArray as $value) {
            if (array_key_exists($value, $this->filters)) {
                $text = call_user_func_array(array($this, $this->filters[$value]), array($text));
            }
        }

        return $text;
    }

    /**
     * Format text acording to Markdown syntax.
     *
     * @param string $text      The text that should be formated.
     *
     * @return string as the formatted html text.
     */
    public function markdown($text) : string
    {
        return \Michelf\Markdown::defaultTransform($this->fixTextFormat($text));
    }

    /**
     * Formats the pre tags and code blocks dippending 
     *              on tripple backticks for pre tags
     *                    and code tag on single backticks.
     *
     * @param string $text          The text to be formatted.
     *
     * @return string as the new string value.
     */
    private function fixTextFormat($text) : string
    {
        $count = 1;
        $myWords = [];

        $replace = str_replace(
            array('```'),
            array('ahsdo123sad'),
            $text
        );

        foreach (explode(" ", $replace) as $value) {
            $placeHolder = $value;

            if (strpos($value, 'ahsdo123sad') !== false) {
                $placeHolder = str_replace(
                    "ahsdo123sad",
                    ($count % 2 !== 0)
                        ? "<pre class='hljs'>"
                        : "</pre>",
                    $value
                );
                $count += 1;
            }
            $myWords[] = $placeHolder;
        }

        $text = implode(" ", $myWords);
        $replace = str_replace(
            array('<pre>', '</pre>'),
            array('<pre class="hljs">', '</pre>'),
            $text
        );

        return $replace;
    }
}
