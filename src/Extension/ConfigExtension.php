<?php
/**
 *
 * Date: 12/04/11
 * @author alinares
 */
namespace ConfigExtension\Extension;

use \Silex\ExtensionInterface;

class ConfigExtension implements ExtensionInterface
{

    function register(\Silex\Application $app)
    {
        $app['config'] = new \ConfigExtension\Model\Config(
            $app['config.path'],
            array(
                'basepath' => $app['basepath'] ,
            )
        );
    }
}
