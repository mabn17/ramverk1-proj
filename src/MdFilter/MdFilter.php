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
        //return MarkdownExtra::defaultTransform($text);
        return \Michelf\Markdown::defaultTransform($text);
    }
}
