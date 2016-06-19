<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/16
 * Time: 15:30
 * Description
 */

namespace Cake\Core;

use Cake\Core\Configure\ConfigEngineInterface;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Utility\Hash;

class Configure
{
    protected static $values = array(
        'debug' => false
    );

    /*
     * engine classes, used to load config files from resources
     */
    protected static $engines = array();

    /*
     * Flag to track whether or not ini_set exists
     */
    protected static $hasIniSet = null;

    public static function load($key, $config = 'default', $merge = true)
    {
        $engine = static::getEngine($config);
        if (!$engine) {
            return false;
        }
        $values = $engine->read($key);

        return static::write($values);
    }

    /*
     * Used to store
     * Usage:
     * ```
     * Configure::write('One.key1', 'value of the Configure::One[key1]');
     * Configure::write(['One.key1' => 'value of the Configure::One[key1]']);
     * Configure::write('One', [
     *     'key1' => 'value of the Configure::One[key1]',
     *     'key2' => 'value of the Configure::One[key2]'
     * ]);
     *
     * Configure::write([
     *     'One.key1' => 'value of the Configure::One[key1]',
     *     'One.key2' => 'value of the Configure::One[key2]'
     * ]);
     * ```
     *
     */
    public static function write($config, $value = null)
    {
        if (!is_array($config)) {
            $config = array($config => $value);
        }

        foreach ($config as $name => $value) {
            static::$values = Hash::insert(static::$values, $name, $value);
        }

        if (isset($config['debug'])) {
            if (static::$hasIniSet === null) {
                static::$hasIniSet = function_exists('ini_set');
            }
            if (static::$hasIniSet) {
                ini_set('display_errors', $config['debug'] ? 1 : 0);
            }
        }
        return true;
    }

    public static function config($name, ConfigEngineInterface $engine)
    {
        static::$engines[$name] = $engine;
    }

    public static function read($var = null)
    {
        if ($var === null) {
            return static::$values;
        }

        return Hash::get(static::$values, $var);
    }

    /*
     * Get the configured engine
     */
    protected static function getEngine($config)
    {
        if (!isset(static::$engines[$config])) {
            if ($config !== 'default') {
                return false;
            }
            static::config($config, new PhpConfig());
        }
        return static::$engines[$config];
    }

    public static function consume($var)
    {
        if (strpos($var, '.') === false) {
            if (!isset(static::$values[$var])) {
                return null;
            }
            $value = static::$values[$var];
            unset(static::$values[$var]);
            return $value;
        }
        $value = Hash::get(static::$values, $var);
        static::delete($var);
        return $value;
    }
}