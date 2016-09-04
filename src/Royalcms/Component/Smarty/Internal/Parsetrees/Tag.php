<?php namespace Royalcms\Component\Smarty\Internal\Parsetrees;

use Royalcms\Component\Smarty\Internal\Templateparser;

/**
 * A complete smarty tag.
 *
 * @package Smarty
 * @subpackage Compiler
 * @ignore
 */
class Tag extends Parsetree
{
    /**
     * Saved block nesting level
     * @var int
     */
    public $saved_block_nesting;

    /**
     * Create parse tree buffer for Smarty tag
     *
     * @param object $parser parser object
     * @param string $data   content
     */
    public function __construct($parser, $data)
    {
        $this->parser = $parser;
        $this->data = $data;
        $this->saved_block_nesting = $parser->block_nesting_level;
    }

    /**
     * Return buffer content
     *
     * @return string content
     */
    public function to_smarty_php()
    {
        return $this->data;
    }

    /**
     * Return complied code that loads the evaluated outout of buffer content into a temporary variable
     *
     * @return string template code
     */
    public function assign_to_var()
    {
        $var = sprintf('$_tmp%d', ++Templateparser::$prefix_number);
        $this->parser->compiler->prefix_code[] = sprintf('<?php ob_start();?>%s<?php %s=ob_get_clean();?>', $this->data, $var);

        return $var;
    }

}