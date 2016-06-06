<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Sectionelse Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class CompileSectionelse extends CompileBase
{
    /**
     * Compiles code for the {sectionelse} tag
     *
     * @param  array  $args     array with attributes from parser
     * @param  object $compiler compiler object
     * @return string compiled code
     */
    public function compile($args, $compiler)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);

        list($openTag, $nocache) = $this->closeTag($compiler, array('section'));
        $this->openTag($compiler, 'sectionelse', array('sectionelse', $nocache));

        return "<?php endfor; else: ?>";
    }

}
