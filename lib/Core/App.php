<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/19
 * Time: 11:27
 */
namespace Cake\Core;

class App
{
    /**
     * Return the class name namespaced. This method checks if the class is defined on the
     * application/plugin, otherwise try to load from the CakePHP core
     *
     * @param string $class Class name
     * @param string $type Type of class
     * @param string $suffix Class name suffix
     * @return bool|string False if the class is not found or namespaced class name
     */
    public static function className($class, $type = '', $suffix = '')
    {
        if (strpos($class, '\\') !== false) {
            return $class;
        }
        list($plugin, $name) = pluginSplit($class);
        $base = $plugin ?: Configure::read('App.namespace');
        $base = str_replace('/', '\\', rtrim($base, '\\'));
        $fullname = '\\' . str_replace('/', '\\', $type . '\\' . $name) . $suffix;
        if (static::_classExistsInBase($fullname, $base)) {
            return $base . $fullname;
        }
        if ($plugin) {
            return false;
        }
        if (static::_classExistsInBase($fullname, 'Cake')) {
            return 'Cake' . $fullname;
        }
        return false;
    }

    /**
     * _classExistsInBase
     *
     * Test isolation wrapper
     *
     * @param string $name Class name.
     * @param string $namespace Namespace.
     * @return bool
     */
    protected static function _classExistsInBase($name, $namespace)
    {
        return class_exists($namespace . $name);
    }
}