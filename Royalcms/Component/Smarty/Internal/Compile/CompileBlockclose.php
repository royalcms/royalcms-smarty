<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile BlockClose Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class CompileBlockclose extends CompileBase
{
    /**
     * Compiles code for the {/block} tag
     *
     * @param array $args     array with attributes from parser
     * @param object $compiler compiler object
     * @return string compiled code
     */
    public function compile($args, $compiler)
    {
        $compiler->has_code = true;
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);
        $saved_data = $this->closeTag($compiler, array('block'));
        $_name = trim($saved_data[0]['name'], "\"'");
        // reset flag for {block} tag
        $compiler->inheritance = $saved_data[1];
        // check if we process an inheritance child template
        if ($compiler->inheritance_child) {
            $name1 = Smarty_Internal_Compile_Block::$nested_block_names[0];
            Smarty_Internal_Compile_Block::$block_data[$name1]['source'] .= "{$compiler->smarty->left_delimiter}/private_child_block{$compiler->smarty->right_delimiter}";
            $level = count(Smarty_Internal_Compile_Block::$nested_block_names);
            array_shift(Smarty_Internal_Compile_Block::$nested_block_names);
            if (!empty(Smarty_Internal_Compile_Block::$nested_block_names)) {
                $name2 = Smarty_Internal_Compile_Block::$nested_block_names[0];
                if (isset($compiler->template->block_data[$name1]) || !$saved_data[0]['hide']) {
                    if (isset(Smarty_Internal_Compile_Block::$block_data[$name1]['child']) || !isset($compiler->template->block_data[$name1])) {
                        Smarty_Internal_Compile_Block::$block_data[$name2]['source'] .= Smarty_Internal_Compile_Block::$block_data[$name1]['source'];
                    } else {
                        if ($compiler->template->block_data[$name1]['mode'] == 'append') {
                            Smarty_Internal_Compile_Block::$block_data[$name2]['source'] .= Smarty_Internal_Compile_Block::$block_data[$name1]['source'] . $compiler->template->block_data[$name1]['source'];
                        } elseif ($compiler->template->block_data[$name1]['mode'] == 'prepend') {
                            Smarty_Internal_Compile_Block::$block_data[$name2]['source'] .= $compiler->template->block_data[$name1]['source'] . Smarty_Internal_Compile_Block::$block_data[$name1]['source'];
                        } else {
                            Smarty_Internal_Compile_Block::$block_data[$name2]['source'] .= $compiler->template->block_data[$name1]['source'];
                        }
                    }
                }
                unset(Smarty_Internal_Compile_Block::$block_data[$name1]);
                $compiler->lex->yypushstate(Smarty_Internal_Templatelexer::CHILDBLOCK);
            } else {
                if (isset($compiler->template->block_data[$name1]) || !$saved_data[0]['hide']) {
                    if (isset($compiler->template->block_data[$name1]) && !isset(Smarty_Internal_Compile_Block::$block_data[$name1]['child'])) {
                        if (strpos($compiler->template->block_data[$name1]['source'], Smarty_Internal_Compile_Block::parent) !== false) {
                            $compiler->template->block_data[$name1]['source'] =
                            str_replace(Smarty_Internal_Compile_Block::parent, Smarty_Internal_Compile_Block::$block_data[$name1]['source'], $compiler->template->block_data[$name1]['source']);
                        } elseif ($compiler->template->block_data[$name1]['mode'] == 'prepend') {
                            $compiler->template->block_data[$name1]['source'] .= Smarty_Internal_Compile_Block::$block_data[$name1]['source'];
                        } elseif ($compiler->template->block_data[$name1]['mode'] == 'append') {
                            $compiler->template->block_data[$name1]['source'] = Smarty_Internal_Compile_Block::$block_data[$name1]['source'] . $compiler->template->block_data[$name1]['source'];
                        }
                    } else {
                        $compiler->template->block_data[$name1]['source'] = Smarty_Internal_Compile_Block::$block_data[$name1]['source'];
                    }
                    $compiler->template->block_data[$name1]['mode'] = 'replace';
                    if ($saved_data[0]['append']) {
                        $compiler->template->block_data[$name1]['mode'] = 'append';
                    }
                    if ($saved_data[0]['prepend']) {
                        $compiler->template->block_data[$name1]['mode'] = 'prepend';
                    }
                }
                unset(Smarty_Internal_Compile_Block::$block_data[$name1]);
                $compiler->lex->yypushstate(Smarty_Internal_Templatelexer::CHILDBODY);
            }
            $compiler->has_code = false;
            return;
        }
        if (isset($compiler->template->block_data[$_name]) && !isset($compiler->template->block_data[$_name]['compiled'])) {
            $_output = Smarty_Internal_Compile_Block::compileChildBlock($compiler, $_name);
        } else {
            if ($saved_data[0]['hide'] && !isset($compiler->template->block_data[$_name]['source'])) {
                $_output = '';
            } else {
                $_output = $compiler->parser->current_buffer->to_smarty_php();
            }
        }
        unset($compiler->template->block_data[$_name]['compiled']);
        // reset flags
        $compiler->parser->current_buffer = $saved_data[2];
        if ($compiler->nocache) {
            $compiler->tag_nocache = true;
        }
        $compiler->nocache = $saved_data[3];
        // $_output content has already nocache code processed
        $compiler->suppressNocacheProcessing = true;

        return $_output;
    }
}
