<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Child Block Close Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class ChildBlockcloseTag extends CompileBase
{


    /**
     * Compiles code for the {/private_child_block} tag
     *
     * @param array $args     array with attributes from parser
     * @param object $compiler compiler object
     * @return boolean true
     */
    public function compile($args, $compiler)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);

        $saved_data = $this->closeTag($compiler, array('private_child_block'));

        // end of child block
        $compiler->popTrace();

        $compiler->nocache = $saved_data[1];
        $compiler->has_code = false;

        return true;
    }
}