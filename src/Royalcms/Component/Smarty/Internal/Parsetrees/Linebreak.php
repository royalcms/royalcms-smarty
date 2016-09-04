<?php namespace Royalcms\Component\Smarty\Internal\Parsetrees;
/**
 * template linebreaks
 *
 * @package Smarty
 * @subpackage Compiler
 * @ignore
 */
class Linebreak extends Parsetree
{
    /**
     * Create buffer with linebreak content
     *
     * @param object $parser parser object
     * @param string $data   linebreak string
     */
    public function __construct($parser, $data)
    {
        $this->parser = $parser;
        $this->data = $data;
    }

    /**
     * Return linebrak
     *
     * @return string linebreak
     */
    public function to_smarty_php()
    {
        return $this->data;
    }

}