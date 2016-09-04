<?php namespace Royalcms\Component\Smarty\Internal\Compile;

use Royalcms\Component\Smarty\Internal\CompileBase;
use Royalcms\Component\Smarty\Internal\Parsetrees\TemplateBuffer;
use Royalcms\Component\Smarty\Internal\Parsetrees\Tag;

/**
 * Smarty Internal Plugin Compile Function
 *
 * Compiles the {function} {/function} tags
 *
 * @package Smarty
 * @subpackage Compiler
 * @author Uwe Tews
 */

/**
 * Smarty Internal Plugin Compile Function Class
 *
 * @package Smarty
 * @subpackage Compiler
 */
class CompileFunction extends CompileBase
{
    /**
     * Attribute definition: Overwrites base class.
     *
     * @var array
     * @see Smarty_Internal_CompileBase
     */
    public $required_attributes = array('name');
    /**
     * Attribute definition: Overwrites base class.
     *
     * @var array
     * @see Smarty_Internal_CompileBase
     */
    public $shorttag_order = array('name');
    /**
     * Attribute definition: Overwrites base class.
     *
     * @var array
     * @see Smarty_Internal_CompileBase
     */
    public $optional_attributes = array('_any');

    /**
     * Compiles code for the {function} tag
     *
     * @param  array   $args      array with attributes from parser
     * @param  object  $compiler  compiler object
     * @param  array   $parameter array with compilation parameter
     * @return boolean true
     */
    public function compile($args, $compiler, $parameter)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);

        if ($_attr['nocache'] === true) {
            $compiler->trigger_template_error('nocache option not allowed', $compiler->lex->taglineno);
        }
        unset($_attr['nocache']);
        $save = array($_attr, $compiler->parser->current_buffer,
            $compiler->template->has_nocache_code, $compiler->template->required_plugins);
        $this->openTag($compiler, 'function', $save);
        $_name = trim($_attr['name'], "'\"");
        unset($_attr['name']);
        // set flag that we are compiling a template function
        $compiler->compiles_template_function = true;
        $compiler->template->properties['function'][$_name]['parameter'] = array();
        $_smarty_tpl = $compiler->template;
        foreach ($_attr as $_key => $_data) {
            eval ('$tmp='.$_data.';');
            $compiler->template->properties['function'][$_name]['parameter'][$_key] = $tmp;
        }
        $compiler->smarty->template_functions[$_name]['parameter'] = $compiler->template->properties['function'][$_name]['parameter'];
        if ($compiler->template->caching) {
            $output = '';
        } else {
            $output = "<?php if (!function_exists('smarty_template_function_{$_name}')) {
    function smarty_template_function_{$_name}(\$_smarty_tpl,\$params) {
    \$saved_tpl_vars = \$_smarty_tpl->tpl_vars;
    foreach (\$_smarty_tpl->smarty->template_functions['{$_name}']['parameter'] as \$key => \$value) {\$_smarty_tpl->tpl_vars[\$key] = new Smarty_variable(\$value);};
    foreach (\$params as \$key => \$value) {\$_smarty_tpl->tpl_vars[\$key] = new \Royalcms\Component\Smarty\Variable(\$value);}?>";
        }
        // Init temporay context
        $compiler->template->required_plugins = array('compiled' => array(), 'nocache' => array());
        $compiler->parser->current_buffer = new TemplateBuffer($compiler->parser);
        $compiler->parser->current_buffer->append_subtree(new Tag($compiler->parser, $output));
        $compiler->template->has_nocache_code = false;
        $compiler->has_code = false;
        $compiler->template->properties['function'][$_name]['compiled'] = '';
        return true;
    }

}


