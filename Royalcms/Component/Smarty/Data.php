<?php namespace Royalcms\Component\Smarty;

use Royalcms\Component\Smarty\Internal\Data as InternalData;

/**
 * class for the Smarty data object
 *
 * The Smarty data object will hold Smarty variables in the current scope
 *
 * @package Smarty
 * @subpackage Template
 */
class Data extends InternalData
{
    /**
     * Smarty object
     *
     * @var Smarty
     */
    public $smarty = null;

    /**
     * create Smarty data object
     *
     * @param Smarty|array $_parent parent template
     * @param Smarty       $smarty  global smarty instance
     */
    public function __construct ($_parent = null, $smarty = null)
    {
        $this->smarty = $smarty;
        if (is_object($_parent)) {
            // when object set up back pointer
            $this->parent = $_parent;
        } elseif (is_array($_parent)) {
            // set up variable values
            foreach ($_parent as $_key => $_val) {
                $this->tpl_vars[$_key] = new Variable($_val);
            }
        } elseif ($_parent != null) {
            throw new SmartyException("Wrong type for template variables");
        }
    }

}