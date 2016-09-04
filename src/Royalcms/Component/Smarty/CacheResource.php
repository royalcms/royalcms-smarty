<?php namespace Royalcms\Component\Smarty;

use Royalcms\Component\Smarty\Internal\Template;
use Royalcms\Component\Smarty\Template\Cached;

/**
* Smarty Internal Plugin
*
* @package Smarty
* @subpackage Cacher
*/

/**
* Cache Handler API
*
* @package Smarty
* @subpackage Cacher
* @author Rodney Rehm
*/
abstract class CacheResource
{
    /**
    * cache for Smarty_CacheResource instances
    * @var array
    */
    public static $resources = array();

    /**
    * resource types provided by the core
    * @var array
    */
    protected static $sysplugins = array(
        'file' => true,
    );

    /**
    * populate Cached Object with meta data from Resource
    *
    * @param Smarty_Template_Cached $cached cached object
    * @param Smarty_Internal_Template $_template template object
    * @return void
    */
    abstract public function populate(Cached $cached, Template $_template);

    /**
    * populate Cached Object with timestamp and exists from Resource
    *
    * @param Smarty_Template_Cached $source cached object
    * @return void
    */
    abstract public function populateTimestamp(Cached $cached);

    /**
    * Read the cached template and process header
    *
    * @param Smarty_Internal_Template $_template template object
    * @param Smarty_Template_Cached $cached cached object
    * @return booelan true or false if the cached content does not exist
    */
    abstract public function process(Template $_template, Cached $cached=null);

    /**
    * Write the rendered template output to cache
    *
    * @param Smarty_Internal_Template $_template template object
    * @param string $content content to cache
    * @return boolean success
    */
    abstract public function writeCachedContent(Template $_template, $content);

    /**
    * Return cached content
    *
    * @param Smarty_Internal_Template $_template template object
    * @param string $content content of cache
    */
    public function getCachedContent(Template $_template)
    {
        if ($_template->cached->handler->process($_template)) {
            ob_start();
            $_template->properties['unifunc']($_template);

            return ob_get_clean();
        }

        return null;
    }

    /**
    * Empty cache
    *
    * @param Smarty $smarty Smarty object
    * @param integer $exp_time expiration time (number of seconds, not timestamp)
    * @return integer number of cache files deleted
    */
    abstract public function clearAll(Smarty $smarty, $exp_time=null);

    /**
    * Empty cache for a specific template
    *
    * @param Smarty $smarty Smarty object
    * @param string $resource_name template name
    * @param string $cache_id cache id
    * @param string $compile_id compile id
    * @param integer $exp_time expiration time (number of seconds, not timestamp)
    * @return integer number of cache files deleted
    */
    abstract public function clear(Smarty $smarty, $resource_name, $cache_id, $compile_id, $exp_time);

    public function locked(Smarty $smarty, Cached $cached)
    {
        // theoretically locking_timeout should be checked against time_limit (max_execution_time)
        $start = microtime(true);
        $hadLock = null;
        while ($this->hasLock($smarty, $cached)) {
            $hadLock = true;
            if (microtime(true) - $start > $smarty->locking_timeout) {
                // abort waiting for lock release
                return false;
            }
            sleep(1);
        }

        return $hadLock;
    }

    public function hasLock(Smarty $smarty, Cached $cached)
    {
        // check if lock exists
        return false;
    }

    public function acquireLock(Smarty $smarty, Cached $cached)
    {
        // create lock
        return true;
    }

    public function releaseLock(Smarty $smarty, Cached $cached)
    {
        // release lock
        return true;
    }

    /**
    * Load Cache Resource Handler
    *
    * @param Smarty $smarty Smarty object
    * @param string $type name of the cache resource
    * @return Smarty_CacheResource Cache Resource Handler
    */
    public static function load(Smarty $smarty, $type = null)
    {
        if (!isset($type)) {
            $type = $smarty->caching_type;
        }

        // try smarty's cache
        if (isset($smarty->_cacheresource_handlers[$type])) {
            return $smarty->_cacheresource_handlers[$type];
        }

        // try registered resource
        if (isset($smarty->registered_cache_resources[$type])) {
            // do not cache these instances as they may vary from instance to instance
            return $smarty->_cacheresource_handlers[$type] = $smarty->registered_cache_resources[$type];
        }
        // try sysplugins dir
        if (isset(self::$sysplugins[$type])) {
            if (!isset(self::$resources[$type])) {
                $cache_resource_class = '\Royalcms\Component\Smarty\Internal\CacheResource\\Resource' . ucfirst($type);
                self::$resources[$type] = new $cache_resource_class();
            }

            return $smarty->_cacheresource_handlers[$type] = self::$resources[$type];
        }
        // try plugins dir
        $cache_resource_class = 'Royalcms\Component\Smarty\CacheResource\\' . ucfirst($type);
        if ($smarty->loadPlugin($cache_resource_class)) {
            if (!isset(self::$resources[$type])) {
                self::$resources[$type] = new $cache_resource_class();
            }

            return $smarty->_cacheresource_handlers[$type] = self::$resources[$type];
        }
        // give up
        throw new SmartyException("Unable to load cache resource '{$type}'");
    }

    /**
    * Invalid Loaded Cache Files
    *
    * @param Smarty $smarty Smarty object
    */
    public static function invalidLoadedCache(Smarty $smarty)
    {
        foreach ($smarty->template_objects as $tpl) {
            if (isset($tpl->cached)) {
                $tpl->cached->valid = false;
                $tpl->cached->processed = false;
            }
        }
    }
}


