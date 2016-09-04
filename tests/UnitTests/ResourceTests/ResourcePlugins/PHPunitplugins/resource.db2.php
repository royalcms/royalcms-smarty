<?php
use Royalcms\Component\Smarty\Resource\Recompiled;
use Royalcms\Component\Smarty\Template\Source;
use Royalcms\Component\Smarty\Internal\Template;

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.db2.php
 * Type:     resource
 * Name:     db
 * Purpose:  Fetches templates from a database
 * -------------------------------------------------------------
 */

class Smarty_Resource_Db2 extends Recompiled
{
    public function populate(Source $source, Template $_template = null)
    {
        $source->filepath = 'db2:';
        $source->uid = sha1($source->resource);
        $source->timestamp = 0;
        $source->exists = true;
    }

    public function getContent(Source $source)
    {
        return '{$x="hello world"}{$x}';
    }
}
