<?php namespace Royalcms\Component\Smarty\Template;

use Royalcms\Component\Smarty\Smarty;
use Royalcms\Component\Smarty\SmartyException;
use Royalcms\Component\Smarty\Resource;
use Royalcms\Component\Smarty\Resource\Uncompiled;
use Royalcms\Component\Smarty\Resource\Recompiled;
use Royalcms\Component\Smarty\Internal\Template;

/**
 * Smarty Resource Data Object
 *
 * Meta Data Container for Template Files
 *
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 *
 * @property integer $timestamp Source Timestamp
 * @property boolean $exists    Source Existence
 * @property boolean $template  Extended Template reference
 * @property string  $content   Source Content
 */
class Source
{
    /**
     * Name of the Class to compile this resource's contents with
     * @var string
     */
    public $compiler_class = null;

    /**
     * Name of the Class to tokenize this resource's contents with
     * @var string
     */
    public $template_lexer_class = null;

    /**
     * Name of the Class to parse this resource's contents with
     * @var string
     */
    public $template_parser_class = null;

    /**
     * Unique Template ID
     * @var string
     */
    public $uid = null;

    /**
     * Template Resource (Smarty_Internal_Template::$template_resource)
     * @var string
     */
    public $resource = null;

    /**
     * Resource Type
     * @var string
     */
    public $type = null;

    /**
     * Resource Name
     * @var string
     */
    public $name = null;

    /**
     * Unique Resource Name
     * @var string
     */
    public $unique_resource = null;

    /**
     * Source Filepath
     * @var string
     */
    public $filepath = null;

    /**
     * Source is bypassing compiler
     * @var boolean
     */
    public $uncompiled = null;

    /**
     * Source must be recompiled on every occasion
     * @var boolean
     */
    public $recompiled = null;

    /**
     * The Components an extended template is made of
     * @var array
     */
    public $components = null;

    /**
     * Resource Handler
     * @var Smarty_Resource
     */
    public $handler = null;

    /**
     * Smarty instance
     * @var Smarty
     */
    public $smarty = null;

    /**
     * create Source Object container
     *
     * @param Smarty_Resource $handler         Resource Handler this source object communicates with
     * @param Smarty          $smarty          Smarty instance this source object belongs to
     * @param string          $resource        full template_resource
     * @param string          $type            type of resource
     * @param string          $name            resource name
     * @param string          $unique_resource unqiue resource name
     */
    public function __construct(Resource $handler, Smarty $smarty, $resource, $type, $name, $unique_resource)
    {
        $this->handler = $handler; // Note: prone to circular references 

        $this->compiler_class = $handler->compiler_class;
        $this->template_lexer_class = $handler->template_lexer_class;
        $this->template_parser_class = $handler->template_parser_class;
        $this->uncompiled = $this->handler instanceof Uncompiled;
        $this->recompiled = $this->handler instanceof Recompiled;

        $this->smarty = $smarty;
        $this->resource = $resource;
        $this->type = $type;
        $this->name = $name;
        $this->unique_resource = $unique_resource;
    }

    /**
     * get a Compiled Object of this source
     *
     * @param  Smarty_Internal_Template $_template template objet
     * @return Smarty_Template_Compiled compiled object
     */
    public function getCompiled(Template $_template)
    {
        // check runtime cache
        $_cache_key = $this->unique_resource . '#' . $_template->compile_id;
        if (isset(Resource::$compileds[$_cache_key])) {
            return Resource::$compileds[$_cache_key];
        }

        $compiled = new Compiled($this);
        $this->handler->populateCompiledFilepath($compiled, $_template);
        $compiled->timestamp = @filemtime($compiled->filepath);
        $compiled->exists = !!$compiled->timestamp;

        // runtime cache
        Resource::$compileds[$_cache_key] = $compiled;

        return $compiled;
    }

    /**
     * render the uncompiled source
     *
     * @param Smarty_Internal_Template $_template template object
     */
    public function renderUncompiled(Template $_template)
    {
        return $this->handler->renderUncompiled($this, $_template);
    }

    /**
     * <<magic>> Generic Setter.
     *
     * @param  string          $property_name valid: timestamp, exists, content, template
     * @param  mixed           $value         new value (is not checked)
     * @throws SmartyException if $property_name is not valid
     */
    public function __set($property_name, $value)
    {
        switch ($property_name) {
            // regular attributes
        	case 'timestamp':
        	case 'exists':
        	case 'content':
        	    // required for extends: only
        	case 'template':
        	    $this->$property_name = $value;
        	    break;

        	default:
        	    throw new SmartyException("invalid source property '$property_name'.");
        }
    }

    /**
     * <<magic>> Generic getter.
     *
     * @param  string          $property_name valid: timestamp, exists, content
     * @return mixed
     * @throws SmartyException if $property_name is not valid
     */
    public function __get($property_name)
    {
        switch ($property_name) {
        	case 'timestamp':
        	case 'exists':
        	    $this->handler->populateTimestamp($this);

        	    return $this->$property_name;

        	case 'content':
        	    return $this->content = $this->handler->getContent($this);

        	default:
        	    throw new SmartyException("source property '$property_name' does not exist.");
        }
    }

}