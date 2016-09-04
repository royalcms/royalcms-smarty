<?php namespace Royalcms\Component\Smarty\Internal\Parsetrees;
/**
 * Template element
 *
 * @package Smarty
 * @subpackage Compiler
 * @ignore
 */
class TemplateBuffer extends Parsetree
{
    /**
     * Array of template elements
     *
     * @var array
     */
    public $subtrees = Array();

    /**
     * Create root of parse tree for template elements
     *
     * @param object $parser parse object
    */
    public function __construct($parser)
    {
        $this->parser = $parser;
    }

    /**
     * Append buffer to subtree
     *
     * @param _smarty_parsetree $subtree
     */
    public function append_subtree(Parsetree $subtree)
    {
        $this->subtrees[] = $subtree;
    }

    /**
     * Sanitize and merge subtree buffers together
     *
     * @return string template code content
     */
    public function to_smarty_php()
    {
        $code = '';
        for ($key = 0, $cnt = count($this->subtrees); $key < $cnt; $key++) {
            if ($key + 2 < $cnt) {
                if ($this->subtrees[$key] instanceof Linebreak && $this->subtrees[$key + 1] instanceof Tag && $this->subtrees[$key + 1]->data == '' && $this->subtrees[$key + 2] instanceof Linebreak) {
                    $key = $key + 1;
                    continue;
                }
                if (substr($this->subtrees[$key]->data, -1) == '<' && $this->subtrees[$key + 1]->data == '' && substr($this->subtrees[$key + 2]->data, -1) == '?') {
                    $key = $key + 2;
                    continue;
                }
            }
            if (substr($code, -1) == '<') {
                $subtree = $this->subtrees[$key]->to_smarty_php();
                if (substr($subtree, 0, 1) == '?') {
                    $code = substr($code, 0, strlen($code) - 1) . '<<?php ?>?' . substr($subtree, 1);
                } elseif ($this->parser->asp_tags && substr($subtree, 0, 1) == '%') {
                    $code = substr($code, 0, strlen($code) - 1) . '<<?php ?>%' . substr($subtree, 1);
                } else {
                    $code .= $subtree;
                }
                continue;
            }
            if ($this->parser->asp_tags && substr($code, -1) == '%') {
                $subtree = $this->subtrees[$key]->to_smarty_php();
                if (substr($subtree, 0, 1) == '>') {
                    $code = substr($code, 0, strlen($code) - 1) . '%<?php ?>>' . substr($subtree, 1);
                } else {
                    $code .= $subtree;
                }
                continue;
            }
            if (substr($code, -1) == '?') {
                $subtree = $this->subtrees[$key]->to_smarty_php();
                if (substr($subtree, 0, 1) == '>') {
                    $code = substr($code, 0, strlen($code) - 1) . '?<?php ?>>' . substr($subtree, 1);
                } else {
                    $code .= $subtree;
                }
                continue;
            }
            $code .= $this->subtrees[$key]->to_smarty_php();
        }

        return $code;
    }

}