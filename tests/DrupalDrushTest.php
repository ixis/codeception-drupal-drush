<?php
/**
 * Contains test for DrupalDrush Codeception module.
 */
namespace Codeception\Module\Test;

use Codeception\Module\DrupalDrush;

class DrupalDrushTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DrupalDrush
     */
    protected $drush;

    public function setUp()
    {
        $config = array(
            "drush-alias" => "@self"
        );

        $this->drush = new DrupalDrush($config);
    }

    public function testGetDrushReturnsProcess()
    {
        $process = $this->drush->getDrush("cc", array('all'));
        $this->assertInstanceOf("Symfony\\Component\\Process\\Process", $process);
    }

    public function testDrushPath()
    {
        $process = $this->drush->getDrush("cc", array("all"), array());
        $this->assertStringStartsWith("'drush'", $process->getCommandLine());

        $process = $this->drush->getDrush("cc", array("all"), array(), "vendor/bin/drush");
        $this->assertStringStartsWith("'vendor/bin/drush'", $process->getCommandLine());
    }

    public function testArgumentsAreEscaped()
    {
        $process = $this->drush->getDrush("cc", array("a b c"), array());
        $this->assertContains('a b c', $process->getCommandLine());
    }

    public function testOptions()
    {
        $process = $this->drush->getDrush("cc", array(), array("u" => "123"));
        $this->assertContains("'-u 123'", $process->getCommandLine());

        $process = $this->drush->getDrush("cc", array(), array("user" => "123"));
        $this->assertContains("'--user=123'", $process->getCommandLine());
    }

    public function testAlias()
    {
        $process = $this->drush->getDrush("cc", array('all'));
        $this->stringStartsWith("'drush' '@self'", $process->getCommandLine());
    }
}
