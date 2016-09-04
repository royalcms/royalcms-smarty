<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Ifclose Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class IfcloseTag extends CompileBase
{
    /**
     * Compiles code for the {/if} tag
     *
     * @param array  $args       array with attributes from parser
     * @param object $compiler   compiler object
     * @param array  $parameter  array with compilation parameter
     * @return string compiled code
     */
    public function compile($args, $compiler, $parameter)
    {
        // must endblock be nocache?
        if ($compiler->nocache) {
            $compiler->tag_nocache = true;
        }
        list($nesting, $compiler->nocache) = $this->closeTag($compiler, array('if', 'else', 'elseif'));
        $tmp = '';
        for ($i = 0; $i < $nesting; $i++) {
            $tmp .= '}';
        }

        return "<?php {$tmp}?>";
    }

}