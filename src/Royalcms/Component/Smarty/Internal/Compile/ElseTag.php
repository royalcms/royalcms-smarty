<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Else Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class ElseTag extends CompileBase
{
    /**
     * Compiles code for the {else} tag
     *
     * @param array  $args       array with attributes from parser
     * @param object $compiler   compiler object
     * @param array  $parameter  array with compilation parameter
     * @return string compiled code
     */
    public function compile($args, $compiler, $parameter)
    {
        list($nesting, $compiler->tag_nocache) = $this->closeTag($compiler, array('if', 'elseif'));
        $this->openTag($compiler, 'else', array($nesting, $compiler->tag_nocache));

        return "<?php } else { ?>";
    }

}