<?php
return array(
    'debug' => true,
    'App' => array(
        'namespace' => 'App',
        'encoding' => env('APP_ENCODING', 'UTF-8'),
        'defaultLocale' => env('APP_DEFAULT_LOCALE', 'en_US'),
        'base' => false,
        'dir' => 'lib',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        // 'baseUrl' => env('SCRIPT_NAME'),
        'fullBaseUrl' => false,
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [APP . 'Template' . DS],
            'locales' => [APP . 'Locale' . DS],
        ],
    ),
    'Error' => array(
        'errorLevel' => E_ALL & ~E_DEPRECATED,
        'exceptionRenderer' => 'Cake\Error\ExceptionRenderer',
        'skipLog' => array(),
        'log' => true,
        'trace' => true
    ),
    'Datasources' => array(
        'default' => array(

        )
    )
);