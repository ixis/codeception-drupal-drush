<?php

namespace Codeception\Module;

use Codeception\Module;
use Symfony\Component\Process\ProcessBuilder;

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
     * @param string $drush
     *   The drush command to use.
     * @param int $return_val
     *   The drush exit code.
     *
     * @return string
     *   Output from the drush command.
     */
    public function executeDrushCommand($command, array $arguments, $options = array(), $drush = 'drush', &$return_val = 0)
    {
        $args = array($drush, $this->config['drush-alias'], "-y", $command);
        $command_args = array_merge($args, $arguments);
        $b = new ProcessBuilder($command_args);

        foreach ($options as $opt => $value) {
            if (!isset($value)) {
                if (strlen($opt) == 1) {
                    $b->add("-$opt");
                } else {
                    $b->add("--$opt");
                }
            } else {
                if (strlen($opt) == 1) {
                    $b->add(
                        sprintf(
                            "-%s %s",
                            $opt,
                            $value
                        )
                    );
                } else {
                    $b->add(
                        sprintf(
                            "--%s=%s",
                            $opt,
                            $value
                        )
                    );
                }
            }
        }

        $this->debugSection('Command', $b->getProcess()->getCommandLine());
        $proc = $b->getProcess();
        $return_val = $proc->mustRun();
        return $proc->getOutput();
    }
}
