<?php
/*
 *  各个函数的解释：
 *      bool trigger_error(string $error_msg [, int $error_type = [E_USER_NOTICE]])
 *      触发一个用户几级别的错误
 *      参数: error_msg: 错误信息
 *            error_type: 错误类型,仅对E_USER系列常量有效
 */

if (version_compare(PHP_VERSION, '5.5.9') < 0) {
    trigger_error('You PHP version must be equal or higher than 5.5.9 to use CakePHP.', E_USER_ERROR);
}

// You can remove this if you are confident you have mbstring installed.
if (!extension_loaded('mbstring')) {
    trigger_error('You must enable the mbstring extension to use CakePHP.', E_USER_ERROR);
}

require __DIR__ . '/paths.php';

require ROOT . DS . 'vendor' . DS . 'autoload.php';
//echo CORE_PATH;
//require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Error\ErrorHandler;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\DispatcherFactory;

try {
    Configure::config('default', new PhpConfig());          //主要用户读取config目录
    Configure::load('app', 'default', false);
} catch (\Expection $e) {
    exit($e->getMessage());
}

if (!Configure::read('debug')) {        //读取debug配置

}

date_default_timezone_set('PRC');

mb_internal_encoding(Configure::read('App.encoding'));

//错误处理
(new ErrorHandler(Configure::read('Error')))->register();

if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

//初始化类
//ConnectionManager::config(Configure::consume('Datasources'));

/*
 * middleware/dispatcher filters
 */
DispatcherFactory::add('Routing');

?>