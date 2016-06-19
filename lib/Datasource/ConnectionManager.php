<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/18
 * Time: 13:16
 */
namespace Cake\Datasource;

class ConnectionManager
{
    protected static $dsnClassMap = array(
        'mysql' => 'Cake\Database\Driver\Mysql'
    );

    public static function config($key, $config = null)
    {
        if (is_array($config)) {
            $config['name'] = $key;
        }

        return static::_config($key, $config);
    }
}