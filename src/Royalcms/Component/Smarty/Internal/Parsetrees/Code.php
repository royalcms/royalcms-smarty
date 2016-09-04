<?php namespace Royalcms\Component\Smarty\Internal\Parsetrees;
/**
 * Code fragment inside a tag.
 *
 * @package Smarty
 * @subpackage Compiler
 * @ignore
 */
class Code extends Parsetree
{
    /**
     * Create parse tree buffer for code fragment
     *
     * @param object $parser parser object
     * @param string $data   content
     */
    public function __construct($parser, $data)
    {
        $this->parser = $parser;
        $this->data = $data;
    }

    /**
     * Return buffer content in parentheses
     *
     * @return string content
     */
    public function to_smarty_php()
    {
        return sprintf("(%s)", $this->data);
    }

}