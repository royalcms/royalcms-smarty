<?php
use Royalcms\Component\Smarty\Internal\CompileBase;

// compiler.testclose.php
class smarty_compiler_testclose extends CompileBase
{
    public function execute($args, $compiler)
    {

        $this->closeTag($compiler, 'test');

        return '';
    }
}
