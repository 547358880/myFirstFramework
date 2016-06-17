<?php
return array(
    'debug' => true,
    'App' => array(
        'encoding' => 'UTF-8'
    ),
    'Error' => array(
        'errorLevel' => E_ALL & ~E_DEPRECATED,
        'exceptionRenderer' => 'Cake\Error\ExceptionRenderer',
        'skipLog' => array(),
        'log' => true,
        'trace' => true
    )
);