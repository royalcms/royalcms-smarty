<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Nocache
 *
 * Compiles the {nocache} {/nocache} tags.
 *
 * @package Smarty
 * @subpackage Compiler
 * @author Uwe Tews
 */

/**
 * Smarty Internal Plugin Compile Nocache Classv
 *
 * @package Smarty
 * @subpackage Compiler
 */
class NocacheTag extends CompileBase
{
    /**
     * Compiles code for the {nocache} tag
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
        if ($_attr['nocache'] === true) {
            $compiler->trigger_template_error('nocache option not allowed', $compiler->lex->taglineno);
        }
        // enter nocache mode
        $compiler->nocache = true;
        // this tag does not return compiled code
        $compiler->has_code = false;

        return true;
    }

}


