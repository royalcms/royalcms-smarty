<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile Child Block Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class ChildBlock extends CompileBase
{

    /**
     * Attribute definition: Overwrites base class.
     *
     * @var array
     * @see Smarty_Internal_CompileBase
     */
    public $required_attributes = array('name', 'file', 'uid', 'line', 'type', 'resource');


    /**
     * Compiles code for the {private_child_block} tag
     *
     * @param array $args     array with attributes from parser
     * @param object $compiler compiler object
     * @return boolean true
    */
    public function compile($args, $compiler)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);

        // update template with original template resource of {block}
        if (trim($_attr['type'], "'") == 'file') {
            $compiler->template->template_resource = realpath(trim($_attr['file'], "'"));
        } else {
            $compiler->template->template_resource = trim($_attr['resource'], "'");
        }
        // source object
        unset ($compiler->template->source);
        $exists = $compiler->template->source->exists;


        // must merge includes
        if ($_attr['nocache'] == true) {
            $compiler->tag_nocache = true;
        }
        $save = array($_attr, $compiler->nocache);

        // set trace back to child block
        $compiler->pushTrace(trim($_attr['file'], "\"'"), trim($_attr['uid'], "\"'"), $_attr['line'] - $compiler->lex->line);

        $this->openTag($compiler, 'private_child_block', $save);

        $compiler->nocache = $compiler->nocache | $compiler->tag_nocache;
        $compiler->has_code = false;

        return true;
    }
}