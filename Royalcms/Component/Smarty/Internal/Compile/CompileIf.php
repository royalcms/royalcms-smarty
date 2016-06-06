<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;

/**
* Smarty Internal Plugin Compile If
*
* Compiles the {if} {else} {elseif} {/if} tags
*
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/

/**
* Smarty Internal Plugin Compile If Class
*
* @package Smarty
* @subpackage Compiler
*/
class CompileIf extends CompileBase
{
    /**
    * Compiles code for the {if} tag
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
        $this->openTag($compiler, 'if', array(1, $compiler->nocache));
        // must whole block be nocache ?
        $compiler->nocache = $compiler->nocache | $compiler->tag_nocache;

        if (!array_key_exists("if condition",$parameter)) {
            $compiler->trigger_template_error("missing if condition", $compiler->lex->taglineno);
        }

        if (is_array($parameter['if condition'])) {
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
            if (is_array($parameter['if condition']['var'])) {
                $_output = "<?php if (!isset(\$_smarty_tpl->tpl_vars[".$parameter['if condition']['var']['var']."]) || !is_array(\$_smarty_tpl->tpl_vars[".$parameter['if condition']['var']['var']."]->value)) \$_smarty_tpl->createLocalArrayVariable(".$parameter['if condition']['var']['var']."$_nocache);\n";
                $_output .= "if (\$_smarty_tpl->tpl_vars[".$parameter['if condition']['var']['var']."]->value".$parameter['if condition']['var']['smarty_internal_index']." = ".$parameter['if condition']['value'].") {?>";
            } else {
                $_output = "<?php if (!isset(\$_smarty_tpl->tpl_vars[".$parameter['if condition']['var']."])) \$_smarty_tpl->tpl_vars[".$parameter['if condition']['var']."] = new Smarty_Variable(null{$_nocache});";
                $_output .= "if (\$_smarty_tpl->tpl_vars[".$parameter['if condition']['var']."]->value = ".$parameter['if condition']['value'].") {?>";
            }

            return $_output;
        } else {
            return "<?php if ({$parameter['if condition']}) {?>";
        }
    }

}

