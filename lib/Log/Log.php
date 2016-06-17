<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/17
 * Time: 13:09
 * Description
 */
namespace Cake\Log;

class Log
{
    protected static $dsnClassMap = array(
        'console' => 'Cake\Log\Engine\ConsoleLog',
        'file' => 'Cake\Log\Engine\FileLog',
        'syslog' => 'Cake\Log\Engine\SyslogLog'
    );

    protected static $dirtyConfig = false;

    /*
     * LogEngineRegistery class
     */
    protected static $registry;

    protected static $levels = array(
        'error'
    );

    protected static $levelMap = array(
        'error' => LOG_ERR
    );

    /*
     * Initializes register and configurations
     */
    protected static function init()
    {
        if (empty(static::$registry)) {
            static::$registry = new LogEngineRegistery();
        }

        if (static::$dirtyConfig) {
            echo '111';
        }

        static::$dirtyConfig = false;
    }

    /*
     * Writes the given message and type to call of the configured log
     * ### Levels:
     *
     * ### Basic usage
     *
     * Write a 'warning' message to the logs:
     *
     * ```
     * Log::write('warning', 'Stuff is broken here');
     * ```
     *
     * ### Using scopes
     */
    public static function write($level, $message, $context = array())
    {
        static::init();
        die('dasd');
    }
}