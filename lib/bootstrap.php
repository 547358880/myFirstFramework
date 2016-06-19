<?php
use Cake\Routing\Router;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/18
 * Time: 11:58
 */
define('TIME_START', microtime(true));

require CAKE . 'basics.php';
Router::reload();