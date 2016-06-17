<?php
/**
 * Created by PhpStorm.
 * User: xujing
 * Date: 2016/6/17
 * Time: 9:00
 * Description
 */
namespace Cake\Error;

class ErrorHandler extends BaseErrorHandler
{
    protected $options = array();

    public function __construct($options = array())
    {
        $defaults = array(
            'log' => true,
            'trace' => false,
            'exceptionRenderer' => 'Cake\Error\ExceptionRenderer'
        );
        $this->options = $options + $defaults;
    }
}