<?php
use Royalcms\Component\Smarty\Smarty;

/**
 * Smarty PHPunit tests of modifier
 *
 * @package PHPunit
 * @author  Rodney Rehm
 */

/**
 * class for modifier tests
 *
 * @backupStaticAttributes enabled
 */
class PluginModifierCountSentencesTest extends PHPUnit_Smarty
{
    public function setUp()
    {
        $this->setUpSmarty(__DIR__);
    }

    public function testDefault()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world."|count_sentences}');
        $this->assertEquals("1", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('eval:{"hello world. I\'m another? Sentence!"|count_sentences}');
        $this->assertEquals("3", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('string:{"hello world.wrong"|count_sentences}');
        $this->assertEquals("0", $this->smarty->fetch($tpl));
    }

    public function testDefaultWithoutMbstring()
    {
        Smarty::$_MBSTRING = false;
        $tpl = $this->smarty->createTemplate('eval:{"hello world."|count_sentences}');
        $this->assertEquals("1", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('eval:{"hello world. I\'m another? Sentence!"|count_sentences}');
        $this->assertEquals("3", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('eval:{"hello world.wrong"|count_sentences}');
        $this->assertEquals("0", $this->smarty->fetch($tpl));
        Smarty::$_MBSTRING = true;
    }

    public function testUmlauts()
    {
        $tpl = $this->smarty->createTemplate('eval:{"hello worldä."|count_sentences}');
        $this->assertEquals("1", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('eval:{"hello worldü. ä\'m another? Sentence!"|count_sentences}');
        $this->assertEquals("3", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('eval:{"hello worlä.ärong"|count_sentences}');
        $this->assertEquals("0", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('eval:{"hello worlä.wrong"|count_sentences}');
        $this->assertEquals("0", $this->smarty->fetch($tpl));
        $tpl = $this->smarty->createTemplate('eval:{"hello world.ärong"|count_sentences}');
        $this->assertEquals("0", $this->smarty->fetch($tpl));
    }
}
