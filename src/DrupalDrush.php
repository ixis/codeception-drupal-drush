<?php

namespace Codeception\Module;

use Codeception\Module;

class DrupalDrush extends Module {
    /**
     * @var array
     *   List of required config fields.
     */
    protected $requiredFields = array('drush-alias');

    /**
     * Execute a drush command.
     *
     * @param string $command
     *   Command to run.
     *   e.g. "cc"
     * @param array $arguments
     *   Array of arguments.
     *   e.g. array("all")
     * @param array $options
     *   Array of options in key => value format.
     *   e.g. array("help" => null, "v" => null, "uid" => "2,3".
     *
     * @return string
     *   Output from the drush command.
     */
    public function executeDrushCommand($command, array $arguments, $options = array(), $drush = 'drush', $return_var = 0)
    {
        $command_args = array($command);
        foreach ($arguments as $arg) {
            $command_args[] = $arg;
        }
        foreach ($options as $opt => $value) {
            if (!isset($value)) {
                if (strlen($opt) == 1) {
                    $command_args[] = "-$opt";
                } else {
                    $command_args[] = "--$opt";
                }
            } else {
                if (strlen($opt) == 1) {
                    $command_args[] = sprintf(
                        "-%s %s",
                        $opt,
                        escapeshellarg($value)
                    );
                } else {
                    $command_args[] = sprintf(
                        "--%s=%s",
                        $opt,
                        escapeshellarg($value)
                    );
                }
            }
        }

        $command = sprintf(
            "%s -y %s %s",
            escapeshellcmd($drush),
            $this->config['drush-alias'],
            implode(" ", array_map('escapeshellarg', $command_args))
        );

        $output = array();
        $this->debugSection('Command', $command);
        exec($command, $output, $return_var);

        $this->assertEquals(0, $return_var, "$command exited with code $return_var");
        return $output;
    }
}
