<?php
/*
* This file is part of the Smarty PHPUnit tests.
*
*/
/*
 * Smarty PHPUnit Bootstrap
 */
// Locate Smarty class and load it
if (!defined('SMARTY_COMPOSER_INSTALL')) {
    foreach (array(__DIR__ . '/../../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php') as $file) {
        if (file_exists($file)) {
            define('SMARTY_COMPOSER_INSTALL', $file);
            break;
        }
    }
    unset($file);
}
if (!class_exists('PHPUnit_Framework_TestCase')) {
    require_once SMARTY_COMPOSER_INSTALL;
}
require_once 'PHPUnit_Smarty.php';
ini_set('date.timezone', 'UTC');


