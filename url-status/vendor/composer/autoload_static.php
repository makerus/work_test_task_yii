<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5ef06e3385f5af9324f230d74c8e4dce
{
    public static $prefixLengthsPsr4 = array (
        'm' => 
        array (
            'makerus\\urlstatus\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'makerus\\urlstatus\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5ef06e3385f5af9324f230d74c8e4dce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5ef06e3385f5af9324f230d74c8e4dce::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5ef06e3385f5af9324f230d74c8e4dce::$classMap;

        }, null, ClassLoader::class);
    }
}
