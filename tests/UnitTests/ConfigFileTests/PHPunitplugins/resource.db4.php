<?php
use Royalcms\Component\Smarty\Resource;
use Royalcms\Component\Smarty\Template\Source;
use Royalcms\Component\Smarty\Internal\Template;

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.db3.php
 * Type:     resource
 * Name:     db
 * Purpose:  Fetches templates from a database
 * -------------------------------------------------------------
 */

class Smarty_Resource_Db4 extends Resource
{
    public function populate(Source $source, Template $_template = null)
    {
        $source->filepath = 'db4:';
        $source->uid = sha1($source->resource);
        $source->timestamp = 0;
        $source->exists = true;
    }

    public function getContent(Source $source)
    {
        return "foo = 'bar'\n";
    }
}
