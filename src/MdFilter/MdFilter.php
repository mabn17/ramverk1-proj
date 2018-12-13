<?php

namespace Anax\MdFilter;

use Michelf\MarkDownExtra;

/**
 * Filter and format text content.
 *
 * @SuppressWarnings(PHMD.UnusedFormalParameter)
 * @SuppressWarnings(PHMD.UnusedPrivateField)
 */
Class MdFilter
{
    /**
     * @var array $filters  Supported filters with method names of
     *                      their respective handler.
     */
    private $filters = [
        "markdown" => "markdown"
    ];


    /**
     * Call each filter on the text and return the processed text.
     *
     * @param string $text      The text to filter.
     * @param array $filters    Array of filters to use.
     *
     * @return string with the formatted text.
     */
    public function parse($text, $filter = ["markdown"]) : string
    {
        $filterArray = explode(",", $filter);
        foreach ($filterArray as $value) {
            if (array_key_exists($value, $this->filters)) {
                $text = call_user_func_array($this, $this->filters[$value], array($text));
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
        // Use commented lines for phpunit but might cause some errors.
        return MarkdownExtra::defaultTransform($text);
        
        // $parser = new MarkdownExtra;
        // $parser->fn_id_prefix = "post22-";
        
        //return $parser->tansform($text);
    }
}
