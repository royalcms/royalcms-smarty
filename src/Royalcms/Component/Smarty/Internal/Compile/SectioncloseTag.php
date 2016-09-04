<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Sectionclose Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class SectioncloseTag extends CompileBase
{
    /**
     * Compiles code for the {/section} tag
     *
     * @param  array  $args     array with attributes from parser
     * @param  object $compiler compiler object
     * @return string compiled code
     */
    public function compile($args, $compiler)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);

        // must endblock be nocache?
        if ($compiler->nocache) {
            $compiler->tag_nocache = true;
        }

        list($openTag, $compiler->nocache) = $this->closeTag($compiler, array('section', 'sectionelse'));

        if ($openTag == 'sectionelse') {
            return "<?php endif; ?>";
        } else {
            return "<?php endfor; endif; ?>";
        }
    }

}