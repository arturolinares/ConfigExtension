<?php
/**
 * 
 * Date: 26/05/11
 * @author alinares
 */
use \ConfigExtension\Model\Config;
require_once __DIR__ . '/../../ConfigExtension/src/ConfigExtension/Model/Config.php';

class ConfigTest extends PHPUnit_Framework_TestCase
{
    // TODO: Test resgister extension

    public function testValueRetrieval()
    {
        $config = new Config(__DIR__.'/data/test.ini', array('sub' => 'HELLO!'));
        $this->assertNull($config->get('not_existent_key'));
        $this->assertSame('no section', $config->get('key_with'), 'No section keys');

        $this->assertSame('test', $config->get('sectionA.value1'), 'Section names are prepended');
        $this->assertSame(
            'value HELLO! quotes',
            $config->get('section with spaces.value1'),
            'Variable replacement'
        );

        $this->assertSame(array(
                'sectionA.value1' => 'test' ,
                'sectionA.value2' => 'test2' ,
            ),
            $config->getSection('sectionA'),
            'Retrieve complete section as an array with prepended section names');

        $this->assertSame(array(
                'value1' => 'test' ,
                'value2' => 'test2' ,
            ),
            $config->getSection('sectionA', true),
            'Retrieve complete section as an array without prepended section names');
    }
}
