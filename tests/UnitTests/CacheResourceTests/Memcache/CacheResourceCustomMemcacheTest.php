<?php
/**
 * Smarty PHPunit tests for cache resource memcache
 *
 * @package PHPunit
 * @author  Uwe Tews
 */

include_once __DIR__ . '/../_shared/CacheResourceTestCommon.php';

/**
 * class for cache resource memcache tests
 *
 * @backupStaticAttributes enabled
 */
class CacheResourceCustomMemcacheTest extends CacheResourceTestCommon
{
    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     *
     */
    public function setUp()
    {
        if (self::$config['cacheResource']['MemcacheEnable'] != 'true') {
            $this->markTestSkipped('Memcache tests are disabled');
        } else {
            if (!class_exists('Memcache')) {
                $this->markTestSkipped('Memcache not available');
            }
        }
        $this->setUpSmarty(__DIR__);
        parent::setUp();
        $this->smarty->setCachingType('memcachetest');
    }


    public function testInit()
    {
        $this->cleanDirs();
    }

    protected function doClearCacheAssertion($a, $b)
    {
        $this->assertEquals(- 1, $b);
    }

    public function testGetCachedFilepathSubDirs()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $sha1 = $tpl->source->uid . '#helloworld_tpl##';
        $this->assertEquals($sha1, $tpl->cached->filepath);
    }

    /**
     * test getCachedFilepath with cache_id
     */
    public function testGetCachedFilepathCacheId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar');
        $sha1 = $tpl->source->uid . '#helloworld_tpl#foo|bar#';
        $this->assertEquals($sha1, $tpl->cached->filepath);
    }

    /**
     * test getCachedFilepath with compile_id
     */
    public function testGetCachedFilepathCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'blar');
        $sha1 = $tpl->source->uid . '#helloworld_tpl##blar';
        $this->assertEquals($sha1, $tpl->cached->filepath);
    }

    /**
     * test getCachedFilepath with cache_id and compile_id
     */
    public function testGetCachedFilepathCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $sha1 = $tpl->source->uid . '#helloworld_tpl#foo|bar#blar';
        $this->assertEquals($sha1, $tpl->cached->filepath);
    }
}
