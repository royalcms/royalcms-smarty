<?php
use Royalcms\Component\Smarty\Internal\CompileBase;

// compiler.test.php
class smarty_compiler_test extends CompileBase
{
    public function compile($args, $compiler)
    {
        $this->required_attributes = array('data');

        $_attr = $this->getAttributes($compiler, $args);

        $this->openTag($compiler, 'test');

        return "<?php echo 'test output'; ?>";
    }
}

// compiler.testclose.php
class smarty_compiler_testclose extends CompileBase
{
    public function compile($args, $compiler)
    {

        $this->closeTag($compiler, 'test');

        return '';
    }
}
