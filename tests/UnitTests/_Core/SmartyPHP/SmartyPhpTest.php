<?php
/**
 * Smarty PHPunit tests SmartyBC code
 *
 * @package PHPunit
 * @author  Uwe Tews
 */

/**
 * class SmartyBC class tests
 *
 * @backupStaticAttributes enabled
 */
class SmartyPhpTest extends PHPUnit_Smarty
{
    public $loadSmarty = true;
    public function setUp()
    {
        $this->setUpSmarty(__DIR__);
    }


    public function testInit()
    {
        $this->cleanDirs();
    }
    /**
     * test {php} tag
     */
    public function testSmartyPhpTag()
    {
        $this->assertEquals('hello world', $this->smarty->fetch('eval:{php} echo "hello world"; {/php}'));
    }
}
