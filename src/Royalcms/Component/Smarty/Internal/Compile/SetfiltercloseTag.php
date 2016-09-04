<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Setfilterclose Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class SetfiltercloseTag extends CompileBase
{
    /**
     * Compiles code for the {/setfilter} tag
     *
     * This tag does not generate compiled output. It resets variable filter.
     *
     * @param  array  $args     array with attributes from parser
     * @param  object $compiler compiler object
     * @return string compiled code
     */
    public function compile($args, $compiler)
    {
        $_attr = $this->getAttributes($compiler, $args);
        // reset variable filter to previous state
        if (count($compiler->variable_filter_stack)) {
            $compiler->template->variable_filters = array_pop($compiler->variable_filter_stack);
        } else {
            $compiler->template->variable_filters = array();
        }
        // this tag does not return compiled code
        $compiler->has_code = false;

        return true;
    }

}