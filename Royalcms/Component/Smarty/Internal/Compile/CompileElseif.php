<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
 * Smarty Internal Plugin Compile ElseIf Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class CompileElseif extends CompileBase
{
    /**
     * Compiles code for the {elseif} tag
     *
     * @param array  $args       array with attributes from parser
     * @param object $compiler   compiler object
     * @param array  $parameter  array with compilation parameter
     * @return string compiled code
     */
    public function compile($args, $compiler, $parameter)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);

        list($nesting, $compiler->tag_nocache) = $this->closeTag($compiler, array('if', 'elseif'));

        if (!array_key_exists("if condition",$parameter)) {
            $compiler->trigger_template_error("missing elseif condition", $compiler->lex->taglineno);
        }

        if (is_array($parameter['if condition'])) {
            $condition_by_assign = true;
            if ($compiler->nocache) {
                $_nocache = ',true';
                // create nocache var to make it know for further compiling
                if (is_array($parameter['if condition']['var'])) {
                    $compiler->template->tpl_vars[trim($parameter['if condition']['var']['var'], "'")] = new Smarty_variable(null, true);
                } else {
                    $compiler->template->tpl_vars[trim($parameter['if condition']['var'], "'")] = new Smarty_variable(null, true);
                }
            } else {
                $_nocache = '';
            }
        } else {
            $condition_by_assign = false;
        }

        if (empty($compiler->prefix_code)) {
            if ($condition_by_assign) {
                $this->openTag($compiler, 'elseif', array($nesting + 1, $compiler->tag_nocache));
                if (is_array($parameter['if condition']['var'])) {
                    $_output = "<?php } else { if (!isset(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]) || !is_array(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]->value)) \$_smarty_tpl->createLocalArrayVariable(" . $parameter['if condition']['var']['var'] . "$_nocache);\n";
                    $_output .= "if (\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]->value" . $parameter['if condition']['var']['smarty_internal_index'] . " = " . $parameter['if condition']['value'] . ") {?>";
                } else {
                    $_output = "<?php  } else { if (!isset(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "])) \$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "] = new Smarty_Variable(null{$_nocache});";
                    $_output .= "if (\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "]->value = " . $parameter['if condition']['value'] . ") {?>";
                }

                return $_output;
            } else {
                $this->openTag($compiler, 'elseif', array($nesting, $compiler->tag_nocache));

                return "<?php } elseif ({$parameter['if condition']}) {?>";
            }
        } else {
            $tmp = '';
            foreach ($compiler->prefix_code as $code)
                $tmp .= $code;
            $compiler->prefix_code = array();
            $this->openTag($compiler, 'elseif', array($nesting + 1, $compiler->tag_nocache));
            if ($condition_by_assign) {
                if (is_array($parameter['if condition']['var'])) {
                    $_output = "<?php } else {?>{$tmp}<?php  if (!isset(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]) || !is_array(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]->value)) \$_smarty_tpl->createLocalArrayVariable(" . $parameter['if condition']['var']['var'] . "$_nocache);\n";
                    $_output .= "if (\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var']['var'] . "]->value" . $parameter['if condition']['var']['smarty_internal_index'] . " = " . $parameter['if condition']['value'] . ") {?>";
                } else {
                    $_output = "<?php } else {?>{$tmp}<?php if (!isset(\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "])) \$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "] = new Smarty_Variable(null{$_nocache});";
                    $_output .= "if (\$_smarty_tpl->tpl_vars[" . $parameter['if condition']['var'] . "]->value = " . $parameter['if condition']['value'] . ") {?>";
                }

                return $_output;
            } else {
                return "<?php } else {?>{$tmp}<?php if ({$parameter['if condition']}) {?>";
            }
        }
    }

}
