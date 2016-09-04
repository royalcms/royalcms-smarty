<?php namespace Royalcms\Component\Smarty\Template;

use Royalcms\Component\Smarty\Smarty;
use Royalcms\Component\Smarty\CacheResource;
use Royalcms\Component\Smarty\Internal\Template;
use Royalcms\Component\Smarty\Internal\Debug;

/**
 * Smarty Resource Data Object
 *
 * Cache Data Container for Template Files
 *
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
class Cached
{
    /**
     * Source Filepath
     * @var string
     */
    public $filepath = false;

    /**
     * Source Content
     * @var string
     */
    public $content = null;

    /**
     * Source Timestamp
     * @var integer
     */
    public $timestamp = false;

    /**
     * Source Existence
     * @var boolean
     */
    public $exists = false;

    /**
     * Cache Is Valid
     * @var boolean
     */
    public $valid = false;

    /**
     * Cache was processed
     * @var boolean
     */
    public $processed = false;

    /**
     * CacheResource Handler
     * @var Smarty_CacheResource
     */
    public $handler = null;

    /**
     * Template Compile Id (Smarty_Internal_Template::$compile_id)
     * @var string
     */
    public $compile_id = null;

    /**
     * Template Cache Id (Smarty_Internal_Template::$cache_id)
     * @var string
     */
    public $cache_id = null;

    /**
     * Id for cache locking
     * @var string
     */
    public $lock_id = null;

    /**
     * flag that cache is locked by this instance
     * @var bool
     */
    public $is_locked = false;

    /**
     * Source Object
     * @var Smarty_Template_Source
     */
    public $source = null;

    /**
     * create Cached Object container
     *
     * @param Smarty_Internal_Template $_template template object
     */
    public function __construct(Template $_template)
    {
        $this->compile_id = $_template->compile_id;
        $this->cache_id = $_template->cache_id;
        $this->source = $_template->source;
        $_template->cached = $this;
        $smarty = $_template->smarty;

        //
        // load resource handler
        //
        $this->handler = $handler = CacheResource::load($smarty); // Note: prone to circular references

        //
        //    check if cache is valid
        //
        if (!($_template->caching == Smarty::CACHING_LIFETIME_CURRENT || $_template->caching == Smarty::CACHING_LIFETIME_SAVED) || $_template->source->recompiled) {
            $handler->populate($this, $_template);

            return;
        }
        while (true) {
            while (true) {
                $handler->populate($this, $_template);
                if ($this->timestamp === false || $smarty->force_compile || $smarty->force_cache) {
                    $this->valid = false;
                } else {
                    $this->valid = true;
                }
                if ($this->valid && $_template->caching == Smarty::CACHING_LIFETIME_CURRENT && $_template->cache_lifetime >= 0 && time() > ($this->timestamp + $_template->cache_lifetime)) {
                    // lifetime expired
                    $this->valid = false;
                }
                if ($this->valid || !$_template->smarty->cache_locking) {
                    break;
                }
                if (!$this->handler->locked($_template->smarty, $this)) {
                    $this->handler->acquireLock($_template->smarty, $this);
                    break 2;
                }
            }
            if ($this->valid) {
                if (!$_template->smarty->cache_locking || $this->handler->locked($_template->smarty, $this) === null) {
                    // load cache file for the following checks
                    if ($smarty->debugging) {
                        Debug::start_cache($_template);
                    }
                    if ($handler->process($_template, $this) === false) {
                        $this->valid = false;
                    } else {
                        $this->processed = true;
                    }
                    if ($smarty->debugging) {
                        Debug::end_cache($_template);
                    }
                } else {
                    continue;
                }
            } else {
                return;
            }
            if ($this->valid && $_template->caching === Smarty::CACHING_LIFETIME_SAVED && $_template->properties['cache_lifetime'] >= 0 && (time() > ($_template->cached->timestamp + $_template->properties['cache_lifetime']))) {
                $this->valid = false;
            }
            if (!$this->valid && $_template->smarty->cache_locking) {
                $this->handler->acquireLock($_template->smarty, $this);

                return;
            } else {
                return;
            }
        }
    }

    /**
     * Write this cache object to handler
     *
     * @param Smarty_Internal_Template $_template template object
     * @param string $content content to cache
     * @return boolean success
     */
    public function write(Template $_template, $content)
    {
        if (!$_template->source->recompiled) {
            if ($this->handler->writeCachedContent($_template, $content)) {
                $this->content = null;
                $this->timestamp = time();
                $this->exists = true;
                $this->valid = true;
                if ($_template->smarty->cache_locking) {
                    $this->handler->releaseLock($_template->smarty, $this);
                }

                return true;
            }
        }

        return false;
    }

}