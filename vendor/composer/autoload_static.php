<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit77441203e54079f75b39d16f03ba6e9a
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit77441203e54079f75b39d16f03ba6e9a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit77441203e54079f75b39d16f03ba6e9a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}