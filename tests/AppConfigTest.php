<?php
/**
 * 
 * Date: 26/05/11
 * @author alinares
 */
require 'phar://' . __DIR__ . '/../../silex/silex.phar/autoload.php';

require_once __DIR__ . '/../../ConfigExtension/src/ConfigExtension/Model/Config.php';
require_once __DIR__ . '/../../ConfigExtension/src/ConfigExtension/Extension/ConfigExtension.php';

class AppConfigTest extends \Silex\WebTestCase
{

    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernel
     */
    public function createApplication()
    {
        $app = new \Silex\Application();
        $app->register(new \ConfigExtension\Extension\ConfigExtension(), array(
                'config.path' => __DIR__.'/data/test.ini',
            ));
        
        return $app;
    }

    public function testRegisterValues()
    {
        $app = $this->createApplication();
        $app['config']->registerSection($app, 'sectionA');

        $this->assertSame(
            'test',
            $app['sectionA.value1'],
            'The keys are registered in the app'
        );
    }
}
