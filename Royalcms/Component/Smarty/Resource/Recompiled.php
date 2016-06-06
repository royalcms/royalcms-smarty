<?php namespace Royalcms\Component\Smarty\Resource;

use Royalcms\Component\Smarty\Resource;
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
 * Base implementation for resource plugins that don't compile cache
 *
 * @package Smarty
 * @subpackage TemplateResources
 */
abstract class Recompiled extends Resource
{
    /**
     * populate Compiled Object with compiled filepath
     *
     * @param  Smarty_Template_Compiled $compiled  compiled object
     * @param  Smarty_Internal_Template $_template template object
     * @return void
     */
    public function populateCompiledFilepath(Compiled $compiled, Template $_template)
    {
        $compiled->filepath = false;
        $compiled->timestamp = false;
        $compiled->exists = false;
    }

}
