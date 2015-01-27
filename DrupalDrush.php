<?php

namespace Codeception\Module;

use Codeception\TestCase;

class DrupalDrush extends \Codeception\Module {
    /**
     * @var array
     *   List of required config fields.
     */
    protected $requiredFields = array('drush-alias');

    public function executeDrushCommand(array $arguments, $options = array())
    {
        $command_args = array();
        foreach ($arguments as $arg) {
            $command_args[] = $arg;
        }

        foreach ($options as $opt => $value) {
            if (isset($value)) {
                $command_args[] = "--" . escapeshellarg($opt);
            } else {
                $command_args[] = sprintf(
                    "--%s=%s",
                    $opt,
                    escapeshellarg($value)
                );
            }
        }

        $command = sprintf(
            "drush -y %s %s",
            $this->config['drush-alias'],
            implode(" ", array_map('escapeshellarg', $command_args));
        );

        var_dump($command);
    }
}
