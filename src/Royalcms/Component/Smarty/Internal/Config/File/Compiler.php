<?php namespace Royalcms\Component\Smarty\Internal\Config\File;

use Royalcms\Component\Smarty\Internal\Config;
use Royalcms\Component\Smarty\Internal\Configfilelexer;
use Royalcms\Component\Smarty\Internal\Configfileparser;
use Royalcms\Component\Smarty\SmartyCompilerException;

/**
 * Smarty Internal Plugin Config File Compiler
 *
 * This is the config file compiler class. It calls the lexer and parser to
 * perform the compiling.
 *
 * @package Smarty
 * @subpackage Config
 * @author Uwe Tews
 */

/**
 * Main config file compiler class
 *
 * @package Smarty
 * @subpackage Config
 */
class Compiler
{
    /**
     * Lexer object
     *
     * @var object
     */
    public $lex;

    /**
     * Parser object
     *
     * @var object
     */
    public $parser;

    /**
     * Smarty object
     *
     * @var Smarty object
     */
    public $smarty;

    /**
     * Smarty object
     *
     * @var Smarty_Internal_Config object
     */
    public $config;

    /**
     * Compiled config data sections and variables
     *
     * @var array
     */
    public $config_data = array();

    /**
     * Initialize compiler
     *
     * @param Smarty $smarty base instance
     */
    public function __construct($smarty)
    {
        $this->smarty = $smarty;
        $this->config_data['sections'] = array();
        $this->config_data['vars'] = array();
    }

    /**
     * Method to compile a Smarty template.
     *
     * @param  Smarty_Internal_Config $config config object
     * @return bool                   true if compiling succeeded, false if it failed
     */
    public function compileSource(Config $config)
    {
        /* here is where the compiling takes place. Smarty
          tags in the templates are replaces with PHP code,
          then written to compiled files. */
        $this->config = $config;
        // get config file source
        $_content = $config->source->content . "\n";
        // on empty template just return
        if ($_content == '') {
            return true;
        }
        // init the lexer/parser to compile the config file
        $lex = new Configfilelexer($_content, $this->smarty);
        $parser = new Configfileparser($lex, $this);
        if ($this->smarty->_parserdebug) $parser->PrintTrace();
        // get tokens from lexer and parse them
        while ($lex->yylex()) {
            if ($this->smarty->_parserdebug) echo "<br>Parsing  {$parser->yyTokenName[$lex->token]} Token {$lex->value} Line {$lex->line} \n";
            $parser->doParse($lex->token, $lex->value);
        }
        // finish parsing process
        $parser->doParse(0, 0);
        $config->compiled_config = '<?php $_config_vars = ' . var_export($this->config_data, true) . '; ?>';
    }

    /**
     * display compiler error messages without dying
     *
     * If parameter $args is empty it is a parser detected syntax error.
     * In this case the parser is called to obtain information about exspected tokens.
     *
     * If parameter $args contains a string this is used as error message
     *
     * @param string $args individual error message or null
     */
    public function trigger_config_file_error($args = null)
    {
        $this->lex = Configfilelexer::instance();
        $this->parser = Configfileparser::instance();
        // get template source line which has error
        $line = $this->lex->line;
        if (isset($args)) {
            // $line--;
        }
        $match = preg_split("/\n/", $this->lex->data);
        $error_text = "Syntax error in config file '{$this->config->source->filepath}' on line {$line} '{$match[$line-1]}' ";
        if (isset($args)) {
            // individual error message
            $error_text .= $args;
        } else {
            // exspected token from parser
            foreach ($this->parser->yy_get_expected_tokens($this->parser->yymajor) as $token) {
                $exp_token = $this->parser->yyTokenName[$token];
                if (isset($this->lex->smarty_token_names[$exp_token])) {
                    // token type from lexer
                    $expect[] = '"' . $this->lex->smarty_token_names[$exp_token] . '"';
                } else {
                    // otherwise internal token name
                    $expect[] = $this->parser->yyTokenName[$token];
                }
            }
            // output parser error message
            $error_text .= ' - Unexpected "' . $this->lex->value . '", expected one of: ' . implode(' , ', $expect);
        }
        throw new SmartyCompilerException($error_text);
    }

}
