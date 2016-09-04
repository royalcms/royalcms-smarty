<?php
/**
 * Smarty PHPunit tests for cache resource file
 *
 * @package PHPunit
 * @author  Uwe Tews
 */
include_once __DIR__ . '/../_shared/CacheResourceTestCommon.php';

/**
 * class for cache resource file tests
 *
 * @backupStaticAttributes enabled
 */
class CacheResourceCustomRegisteredTest extends CacheResourceTestCommon
{
    public function setUp()
    {
        if (self::$config['cacheResource']['MysqlEnable'] != 'true') {
            $this->markTestSkipped('mysql tests are disabled');
        }
        if (self::$init) {
            $this->getConnection();
        }
        $this->setUpSmarty(__DIR__);
        parent::setUp();
        if (!class_exists('Smarty_CacheResource_Mysqltest', false)) {
            require_once(__DIR__ . "/../_shared/PHPunitplugins/cacheresource.mysqltest.php");
        }
        $this->smarty->setCachingType('foobar');
        $this->smarty->registerCacheResource('foobar', new Smarty_CacheResource_Mysqltest());
    }

    public function testInit()
    {
        $this->cleanDirs();
        $this->initMysqlCache();
    }
}
