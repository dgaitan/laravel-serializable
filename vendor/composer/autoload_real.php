<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInite947fd49b33dc894bfb7f7624fe0a3b8
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInite947fd49b33dc894bfb7f7624fe0a3b8', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInite947fd49b33dc894bfb7f7624fe0a3b8', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInite947fd49b33dc894bfb7f7624fe0a3b8::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}