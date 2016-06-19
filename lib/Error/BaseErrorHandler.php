<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/17
 * Time: 9:06
 * Description
 */
namespace Cake\Error;

use Cake\Core\Configure;
//use Cake\Log\Log;

abstract class BaseErrorHandler
{
    public function register()
    {
        $level = -1;
        if (isset($this->options['errorLevel'])) {
            $level = $this->options['errorLevel'];
        }

        /*
         * set__error_handler(一般用户处理用户触发的错误,警告等)
         * register_shutdown_function(一般用户处理中断程序错误, 如方法不存在，内存不足等)
         */
        error_reporting($level);
        set_error_handler(array($this, 'handleError'), $level); //用户自定义错误处理,在特定条件下触发的一个错误(trigger_error)
        register_shutdown_function(function()
        {
            $error = error_get_last();
            if (!is_array($error)) {
                return;
            }
            $fatals = array(
                E_USER_RROR,
                E_ERROR,
                E_PARSE
            );
            if (!in_array($error['type'], $fatals, true)) {
                return;
            }
            $this->handleFatalError(            //记录错误
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line']
            );
        });
    }

    /*
     * 函数: handler(int $errno, string $errstr [, string $errfile [, int $errline [, array $errcontext]]])
     *  errno: 错误级别
     *  errstr 错误信息
     *  errfile: 错误文件名
     *  errline 错误行号
     *  errcontext
     *
     *  如果函数返回false,标准错误处理会继续被调用
     */
    public function handleError($errno, $errstr, $errfile = null, $errline = null, $context = null)
    {
        echo $errno . '-----' . $errstr . '-----' .  $errfile . '------' . $errline;
        if (error_reporting() === 0) {
            return false;
        }

        list($error, $log) = $this->mapErrorCode($errno);
        if ($log === LOG_ERR) {         //发生错误
            return $this->handleFatalError($errno, $errstr, $errfile, $errline);
        }
        $data = array(
            'level' => $log,
            'code' => $errno,
            'error' => $error,
            'description' => $errstr,
            'file' => $errfile,
            'line' => $errline
        );

        $debug = Configure::read('debug');
        return true;
    }

    /*
     * Log a fatal error
     */
    public function handleFatalError($code, $description, $file, $line)
    {
        $data = array(
            'code' => $code,
            'description' => $description,
            'file' => $file,
            'line' => $line,
            'error' => 'Fatal Error'
        );
        $this->logError(LOG_ERR, $data);            //记录日志
        return true;
    }

    /*
     * Log an error
     */
    protected function logError($level, $data)
    {
        $message = sprintf(
            '%s (%s): %s in [%s, line %s]',
            $data['error'],
            $data['code'],
            $data['description'],
            $data['file'],
            $data['line']
        );

        if (!empty($this->options['trace'])) {

        }
        $message .= "\n\n";
        return ;
    //    return Log::write($level, $message);        //记录日志
    }

    public static function mapErrorCode($code)
    {
        $levelMap = array(
            E_PARSE => 'error',
            E_ERROR => 'error',
            E_CORE_ERROR => 'error',
            E_COMPILE_ERROR => 'error',
            E_USER_ERROR => 'error',
            E_WARNING => 'warning',
            E_COMPILE_WARNING => 'warning',
            E_RECOVERABLE_ERROR => 'warning',
            E_NOTICE => 'notice',
            E_USER_NOTICE => 'notice',
            E_STRICT => 'strict',
            E_DEPRECATED => 'deprecated',
            E_USER_DEPRECATED => 'deprecated',
        );

        $logMap = array(
            'error' => LOG_ERR,
            'warning' => LOG_WARNING,
            'notice' => LOG_NOTICE,
            'strict' => LOG_NOTICE,
            'deprecated' => LOG_NOTICE,
        );

        $error = $levelMap[$code];
        $log = $logMap[$error];
        return array($error, $log);
    }
}