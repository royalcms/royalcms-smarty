<?php namespace Royalcms\Component\Smarty\Internal\Parsetrees;
/**
 * Raw chars as part of a double quoted string.
 *
 * @package Smarty
 * @subpackage Compiler
 * @ignore
 */
class DqContent extends Parsetree
{
    /**
     * Create parse tree buffer with string content
     *
     * @param object $parser parser object
     * @param string $data   string section
     */
    public function __construct($parser, $data)
    {
        $this->parser = $parser;
        $this->data = $data;
    }

    /**
     * Return content as double quoted string
     *
     * @return string doubled quoted string
     */
    public function to_smarty_php()
    {
        return '"' . $this->data . '"';
    }

}