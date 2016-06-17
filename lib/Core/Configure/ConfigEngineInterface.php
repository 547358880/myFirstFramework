<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/16
 * Time: 15:43
 * Description
 */
namespace Cake\Core\Configure;

interface ConfigEngineInterface
{
    public function read($key);

    public function dump($key, array $data);
}