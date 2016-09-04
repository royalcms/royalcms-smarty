<?php namespace Royalcms\Component\Smarty\Internal\Parsetrees;
/**
 * template text
 *
 * @package Smarty
 * @subpackage Compiler
 * @ignore
 */
class Text extends Parsetree
{
    /**
     * Create template text buffer
     *
     * @param object $parser parser object
     * @param string $data   text
     */
    public function __construct($parser, $data)
    {
        $this->parser = $parser;
        $this->data = $data;
    }

    /**
     * Return buffer content
     *
     * @return strint text
     */
    public function to_smarty_php()
    {
        return $this->data;
    }

}