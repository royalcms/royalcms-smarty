<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Debug
 *
 * Compiles the {debug} tag.
 * It opens a window the the Smarty Debugging Console.
 *
 * @package Smarty
 * @subpackage Compiler
 * @author Uwe Tews
 */

/**
 * Smarty Internal Plugin Compile Debug Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class DebugTag extends CompileBase
{
    /**
     * Compiles code for the {debug} tag
     *
     * @param  array  $args     array with attributes from parser
     * @param  object $compiler compiler object
     * @return string compiled code
     */
    public function compile($args, $compiler)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);

        // compile always as nocache
        $compiler->tag_nocache = true;

        // display debug template
        $_output = "<?php \$_smarty_tpl->smarty->loadPlugin('\Royalcms\Component\Smarty\Internal\Debug'); \Royalcms\Component\Smarty\Internal\Debug::display_debug(\$_smarty_tpl); ?>";

        return $_output;
    }

}
