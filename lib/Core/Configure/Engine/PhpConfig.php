<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/16
 * Time: 15:40
 * Description
 */
namespace Cake\Core\Configure\Engine;

use Cake\Core\Configure\ConfigEngineInterface;
use Cake\Core\Configure\FileConfigTrait;
use Cake\Core\Exception\Exception;

class PhpConfig implements ConfigEngineInterface
{

    protected $extension = '.php';

    public function __construct($path = null)
    {
        if ($path === null)
        {
            $path = CONFIG;
        }
        $this->path = $path;
    }

    public function read($key)
    {
        $file = $this->getFilePath($key, true);
        $return = include $file;
        if (is_array($return)) {
            return $return;
        }

        if (!isset($config)) {
            if (!isset($config)) {
                throw new Exception(sprintf('Config file "%s" did not return an array', $key . '.php'));
            }

            return $config;
        }
    }

    public function dump($key, array $data)
    {

    }

    protected function getFilePath($key, $checkExists = false)
    {
        if (strpos($key, '..') !== false) {

        }

        list($plguin, $key) = pluginSplit($key);
        if ($plguin) {

        } else {
            $file = $this->path . $key;
        }

        $file .= $this->extension;
        if (!$checkExists || is_file($file)) {
            return $file;
        }

        if (is_file(realpath($file))) {
            return realpath($file);
        }
        throw new Exception(sprintf('Could not load configuration file: %s', $file));
    }
}