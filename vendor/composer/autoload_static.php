<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5ddc3d1db3ca42b3269ed4c86cd92a6d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'ProjetoMvc\\' => 11,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ProjetoMvc\\' => 
        array (
            0 => __DIR__ . '/..' . '/projetoMvc',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5ddc3d1db3ca42b3269ed4c86cd92a6d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5ddc3d1db3ca42b3269ed4c86cd92a6d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
