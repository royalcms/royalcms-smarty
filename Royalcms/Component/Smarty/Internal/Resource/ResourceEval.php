<?php namespace Royalcms\Component\Smarty\Internal\Resource;

use Royalcms\Component\Smarty\Smarty;
use Royalcms\Component\Smarty\Resource\Recompiled;
use Royalcms\Component\Smarty\Template\Source;
use Royalcms\Component\Smarty\Internal\Template;

/**
 * Smarty Internal Plugin Resource Eval
 *
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews
 * @author Rodney Rehm
 */

/**
 * Smarty Internal Plugin Resource Eval
 *
 * Implements the strings as resource for Smarty template
 *
 * {@internal unlike string-resources the compiled state of eval-resources is NOT saved for subsequent access}}
 *
 * @package Smarty
 * @subpackage TemplateResources
 */
class ResourceEval extends Recompiled
{
    /**
     * populate Source Object with meta data from Resource
     *
     * @param  Smarty_Template_Source   $source    source object
     * @param  Smarty_Internal_Template $_template template object
     * @return void
     */
    public function populate(Source $source, Template $_template=null)
    {
        $source->uid = $source->filepath = sha1($source->name);
        $source->timestamp = false;
        $source->exists = true;
    }

    /**
     * Load template's source from $resource_name into current template object
     *
     * @uses decode() to decode base64 and urlencoded template_resources
     * @param  Smarty_Template_Source $source source object
     * @return string                 template source
     */
    public function getContent(Source $source)
    {
        return $this->decode($source->name);
    }

    /**
     * decode base64 and urlencode
     *
     * @param  string $string template_resource to decode
     * @return string decoded template_resource
     */
    protected function decode($string)
    {
        // decode if specified
        if (($pos = strpos($string, ':')) !== false) {
            if (!strncmp($string, 'base64', 6)) {
                return base64_decode(substr($string, 7));
            } elseif (!strncmp($string, 'urlencode', 9)) {
                return urldecode(substr($string, 10));
            }
        }

        return $string;
    }

    /**
     * modify resource_name according to resource handlers specifications
     *
     * @param  Smarty $smarty        Smarty instance
     * @param  string $resource_name resource_name to make unique
     * @param  boolean $is_config    flag for config resource
     * @return string unique resource name
     */
    protected function buildUniqueResourceName(Smarty $smarty, $resource_name, $is_config = false)
    {
        return get_class($this) . '#' .$this->decode($resource_name);
    }

    /**
     * Determine basename for compiled filename
     *
     * @param  Smarty_Template_Source $source source object
     * @return string                 resource's basename
     */
    protected function getBasename(Source $source)
    {
        return '';
    }

}