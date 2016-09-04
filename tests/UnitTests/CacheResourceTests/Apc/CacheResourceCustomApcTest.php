<?php
/**
 * Smarty PHPunit tests for cache resource file
 *
 * @package PHPunit
 * @author  Uwe Tews
 */

include_once __DIR__ . '/../Memcache/CacheResourceCustomMemcacheTest.php';

/**
 * class for cache resource file tests
 *
 * @backupStaticAttributes enabled
 */
class CacheResourceCustomApcTest extends CacheResourceCustomMemcacheTest
{
   public function setUp()
    {
        if (self::$config['cacheResource']['ApcEnable'] != 'true') {
            $this->markTestSkipped('Apc tests are disabled');
        } else {
            if (!function_exists('apc_cache_info') || ini_get('apc.enable_cli')) {
                $this->markTestSkipped('APC cache not available');
            }
        }
        $this->setUpSmarty(__DIR__);
        parent::setUp();
        $this->smarty->addPluginsDir(SMARTY_DIR . '../demo/plugins/');
    }
}
