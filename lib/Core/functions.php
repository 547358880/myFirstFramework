<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/16
 * Time: 16:18
 * Description
 */

if (!function_exists('pluginSplit')) {
    function pluginSplit($name, $dotAppend = false, $plugin = null)
    {
        if (strpos($name, '.') !== false) {
            $parts = explode('.', $name, 2);
            print_r($parts);
            if ($dotAppend) {
                $parts[0] .= '.';
            }
            return $parts;
        }
        return array($plugin, $name);
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        if ($key === 'HTTPS') {
            if (isset($_SERVER['HTTPS'])) {
                return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            }
            return (strpos(env('SCRIPT_URI'), 'https://') === 0);
        }
        return $default;
    }
}

if (!function_exists('pr')) {
    /*
     * print_r() convenience function
     */

    function pr($var)
    {
        $template = '<pre class="pr">%s</pre>';
        printf($template, trim(print_r($var, true)));
    }
}