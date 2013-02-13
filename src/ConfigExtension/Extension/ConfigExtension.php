<?php
/**
 *
 * Date: 12/04/11
 * @author alinares
 */
namespace ConfigExtension\Extension;

use \Silex\ServiceProviderInterface;
use \ConfigExtension\Model\Config;

/**
 * Config silex extension
 */
class ConfigExtension implements ServiceProviderInterface
{

    function register(\Silex\Application $app)
    {
        $app['config'] = $app->share(function () use ($app) {
            return new Config(
                $app['config.path'],
                isset($app['config.replacements']) ? $app['config.replacements'] : array()
            );
        });
    }
}
