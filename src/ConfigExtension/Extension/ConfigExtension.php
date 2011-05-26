<?php
/**
 *
 * Date: 12/04/11
 * @author alinares
 */
namespace ConfigExtension\Extension;

use \Silex\ExtensionInterface;
use \ConfigExtension\Model\Config;

/**
 * Config silex extension
 */
class ConfigExtension implements ExtensionInterface
{

    function register(\Silex\Application $app)
    {
        if (isset($app['config.class_path']))
        {
            /** @var $loader \Symfony\Component\ClassLoader\UniversalClassLoader */
            $loader = $app['autoloader'];
            $class_path = $app['config.class_path'];
            $loader->registerNamespace('ConfigExtension', $class_path);
        }


        $app['config'] = new Config(
            $app['config.path'],
            isset($app['config.replacements']) ?: array()
        );
    }
}
