<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Nocacheclose Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class NocachecloseTag extends CompileBase
{
    /**
     * Compiles code for the {/nocache} tag
     *
     * This tag does not generate compiled output. It only sets a compiler flag.
     *
     * @param  array  $args     array with attributes from parser
     * @param  object $compiler compiler object
     * @return bool
     */
    public function compile($args, $compiler)
    {
        $_attr = $this->getAttributes($compiler, $args);
        // leave nocache mode
        $compiler->nocache = false;
        // this tag does not return compiled code
        $compiler->has_code = false;

        return true;
    }

}