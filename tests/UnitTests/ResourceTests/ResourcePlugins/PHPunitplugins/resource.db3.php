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

class Smarty_Resource_Db3 extends Resource
{
    public function populate(Source $source, Template $_template = null)
    {
        $source->filepath = 'db3:';
        $source->uid = sha1($source->resource);
        $source->timestamp = 0;
        $source->exists = true;
    }

    public function getContent(Source $source)
    {
        return '{$x="hello world"}{$x}';
    }

    public function getCompiledFilepath(Template $_template)
    {
        return false;
    }
}
