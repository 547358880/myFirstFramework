<?php
/*
 * 定义各种路径变量
 */
if (!defined('DS')) {               //  值为 \
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT', dirname(__DIR__));

define('APP_DIR', 'src');

define('APP', ROOT . DS . APP_DIR . DS);

define('CONFIG', ROOT . DS . 'config' . DS);

define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

define('TESTS', ROOT . DS . 'tests' . DS);

define('TMP', ROOT . DS . 'tmp' . DS);

define('LOGS', ROOT . DS . 'logs' . DS);

define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');

define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);