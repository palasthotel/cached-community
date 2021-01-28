<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7393cf504a41d6785c3ed3c2fd979839
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'CachedCommunity\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'CachedCommunity\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'CachedCommunity\\API' => __DIR__ . '/../..' . '/classes/api.php',
        'CachedCommunity\\ActivityComments' => __DIR__ . '/../..' . '/classes/ActivityComments.php',
        'CachedCommunity\\ActivityQuery' => __DIR__ . '/../..' . '/classes/ActivityQuery.php',
        'CachedCommunity\\AdminBar' => __DIR__ . '/../..' . '/classes/AdminBar.php',
        'CachedCommunity\\Ajax' => __DIR__ . '/../..' . '/classes/Ajax.php',
        'CachedCommunity\\Assets' => __DIR__ . '/../..' . '/classes/Assets.php',
        'CachedCommunity\\Cache' => __DIR__ . '/../..' . '/classes/Cache.php',
        'CachedCommunity\\Request' => __DIR__ . '/../..' . '/classes/Request.php',
        'CachedCommunity\\Settings' => __DIR__ . '/../..' . '/classes/Settings.php',
        'CachedCommunity\\SpecialCookie' => __DIR__ . '/../..' . '/classes/SpecialCookie.php',
        'CachedCommunity\\_Component' => __DIR__ . '/../..' . '/classes/_Component.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7393cf504a41d6785c3ed3c2fd979839::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7393cf504a41d6785c3ed3c2fd979839::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7393cf504a41d6785c3ed3c2fd979839::$classMap;

        }, null, ClassLoader::class);
    }
}
