<?php namespace Royalcms\Component\Smarty\Internal\TPC;

class yyStackEntry
{
    public $stateno;       /* The state-number */
    public $major;         /* The major token value.  This is the code
    ** number for the token at this stack level */
    public $minor; /* The user-supplied minor token value.  This
    ** is the value of the token  */
};