<?php namespace Royalcms\Component\Smarty\Template;
/**
 * Smarty Resource Data Object
 *
 * Meta Data Container for Template Files
 *
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 *
 * @property string $content compiled content
 */
class Compiled
{
    /**
     * Compiled Filepath
     * @var string
     */
    public $filepath = null;

    /**
     * Compiled Timestamp
     * @var integer
     */
    public $timestamp = null;

    /**
     * Compiled Existence
     * @var boolean
     */
    public $exists = false;

    /**
     * Compiled Content Loaded
     * @var boolean
     */
    public $loaded = false;

    /**
     * Template was compiled
     * @var boolean
     */
    public $isCompiled = false;

    /**
     * Source Object
     * @var Smarty_Template_Source
     */
    public $source = null;

    /**
     * Metadata properties
     *
     * populated by Smarty_Internal_Template::decodeProperties()
     * @var array
     */
    public $_properties = null;

    /**
     * create Compiled Object container
     *
     * @param Smarty_Template_Source $source source object this compiled object belongs to
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
    }

}