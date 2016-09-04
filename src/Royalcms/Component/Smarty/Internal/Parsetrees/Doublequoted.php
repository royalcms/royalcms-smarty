<?php namespace Royalcms\Component\Smarty\Internal\Parsetrees;
/**
 * Double quoted string inside a tag.
 *
 * @package Smarty
 * @subpackage Compiler
 * @ignore
 */
class Doublequoted extends Parsetree
{
    /**
     * Create parse tree buffer for double quoted string subtrees
     *
     * @param object            $parser  parser object
     * @param _smarty_parsetree $subtree parsetree buffer
     */
    public function __construct($parser, Parsetree $subtree)
    {
        $this->parser = $parser;
        $this->subtrees[] = $subtree;
        if ($subtree instanceof Tag) {
            $this->parser->block_nesting_level = count($this->parser->compiler->_tag_stack);
        }
    }

    /**
     * Append buffer to subtree
     *
     * @param _smarty_parsetree $subtree parsetree buffer
     */
    public function append_subtree(Parsetree $subtree)
    {
        $last_subtree = count($this->subtrees) - 1;
        if ($last_subtree >= 0 && $this->subtrees[$last_subtree] instanceof Tag && $this->subtrees[$last_subtree]->saved_block_nesting < $this->parser->block_nesting_level) {
            if ($subtree instanceof Code) {
                $this->subtrees[$last_subtree]->data .= '<?php echo ' . $subtree->data . ';?>';
            } elseif ($subtree instanceof DqContent) {
                $this->subtrees[$last_subtree]->data .= '<?php echo "' . $subtree->data . '";?>';
            } else {
                $this->subtrees[$last_subtree]->data .= $subtree->data;
            }
        } else {
            $this->subtrees[] = $subtree;
        }
        if ($subtree instanceof Tag) {
            $this->parser->block_nesting_level = count($this->parser->compiler->_tag_stack);
        }
    }

    /**
     * Merge subtree buffer content together
     *
     * @return string compiled template code
     */
    public function to_smarty_php()
    {
        $code = '';
        foreach ($this->subtrees as $subtree) {
            if ($code !== "") {
                $code .= ".";
            }
            if ($subtree instanceof Tag) {
                $more_php = $subtree->assign_to_var();
            } else {
                $more_php = $subtree->to_smarty_php();
            }

            $code .= $more_php;

            if (!$subtree instanceof DqContent) {
                $this->parser->compiler->has_variable_string = true;
            }
        }

        return $code;
    }

}