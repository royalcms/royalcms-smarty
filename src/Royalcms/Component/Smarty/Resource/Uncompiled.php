<?php namespace Royalcms\Component\Smarty\Resource;

use Royalcms\Component\Smarty\Resource;
use Royalcms\Component\Smarty\Template\Source;
use Royalcms\Component\Smarty\Template\Compiled;
use Royalcms\Component\Smarty\Internal\Template;

/**
 * Smarty Resource Plugin
 *
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */

/**
 * Smarty Resource Plugin
 *
 * Base implementation for resource plugins that don't use the compiler
 *
 * @package Smarty
 * @subpackage TemplateResources
 */
abstract class Uncompiled extends Resource
{
    /**
     * Render and output the template (without using the compiler)
     *
     * @param  Smarty_Template_Source   $source    source object
     * @param  Smarty_Internal_Template $_template template object
     * @throws SmartyException          on failure
     */
    abstract public function renderUncompiled(Source $source, Template $_template);

    /**
     * populate compiled object with compiled filepath
     *
     * @param Smarty_Template_Compiled $compiled  compiled object
     * @param Smarty_Internal_Template $_template template object (is ignored)
     */
    public function populateCompiledFilepath(Compiled $compiled, Template $_template)
    {
        $compiled->filepath = false;
        $compiled->timestamp = false;
        $compiled->exists = false;
    }

}
