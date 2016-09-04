<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;
use Royalcms\Component\Smarty\Internal\Parsetrees\Linebreak;

/**
 * Smarty Internal Plugin Compile Functionclose Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class FunctioncloseTag extends CompileBase
{
    /**
     * Compiles code for the {/function} tag
     *
     * @param  array   $args      array with attributes from parser
     * @param  object  $compiler  compiler object
     * @param  array   $parameter array with compilation parameter
     * @return boolean true
     */
    public function compile($args, $compiler, $parameter)
    {
        $_attr = $this->getAttributes($compiler, $args);
        $saved_data = $this->closeTag($compiler, array('function'));
        $_name = trim($saved_data[0]['name'], "'\"");
        // build plugin include code
        $plugins_string = '';
        if (!empty($compiler->template->required_plugins['compiled'])) {
            $plugins_string = '<?php ';
            foreach ($compiler->template->required_plugins['compiled'] as $tmp) {
                foreach ($tmp as $data) {
                    $plugins_string .= "if (!is_callable('{$data['function']}')) include '{$data['file']}';\n";
                }
            }
            $plugins_string .= '?>';
        }
        if (!empty($compiler->template->required_plugins['nocache'])) {
            $plugins_string .= "<?php echo '/*%%SmartyNocache:{$compiler->template->properties['nocache_hash']}%%*/<?php ";
            foreach ($compiler->template->required_plugins['nocache'] as $tmp) {
                foreach ($tmp as $data) {
                    $plugins_string .= "if (!is_callable(\'{$data['function']}\')) include \'{$data['file']}\';\n";
                }
            }
            $plugins_string .= "?>/*/%%SmartyNocache:{$compiler->template->properties['nocache_hash']}%%*/';?>\n";
        }
        // remove last line break from function definition
        $last = count($compiler->parser->current_buffer->subtrees) - 1;
        if ($compiler->parser->current_buffer->subtrees[$last] instanceof Linebreak) {
            unset($compiler->parser->current_buffer->subtrees[$last]);
        }
        // if caching save template function for possible nocache call
        if ($compiler->template->caching) {
            $compiler->template->properties['function'][$_name]['compiled'] .= $plugins_string
            . $compiler->parser->current_buffer->to_smarty_php();
            $compiler->template->properties['function'][$_name]['nocache_hash'] = $compiler->template->properties['nocache_hash'];
            $compiler->template->properties['function'][$_name]['has_nocache_code'] = $compiler->template->has_nocache_code;
            $compiler->template->properties['function'][$_name]['called_functions'] = $compiler->called_functions;
            $compiler->called_functions = array();
            $compiler->smarty->template_functions[$_name] = $compiler->template->properties['function'][$_name];
            $compiler->has_code = false;
            $output = true;
        } else {
            $output = $plugins_string . $compiler->parser->current_buffer->to_smarty_php() . "<?php \$_smarty_tpl->tpl_vars = \$saved_tpl_vars;
foreach (Smarty::\$global_tpl_vars as \$key => \$value) if(!isset(\$_smarty_tpl->tpl_vars[\$key])) \$_smarty_tpl->tpl_vars[\$key] = \$value;}}?>\n";
        }
        // reset flag that we are compiling a template function
        $compiler->compiles_template_function = false;
        // restore old compiler status
        $compiler->parser->current_buffer = $saved_data[1];
        $compiler->template->has_nocache_code = $compiler->template->has_nocache_code | $saved_data[2];
        $compiler->template->required_plugins = $saved_data[3];

        return $output;
    }

}