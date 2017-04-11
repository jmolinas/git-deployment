<?php

namespace JMolinas\GitDeployment;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Composer
{
    private function exec($dir, $home)
    {
        $process = new Process("composer install && composer self-update COMPOSER_HOME = {$home}/.composer", $dir);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    public function run($dir, $home)
    {
        if (file_exists($dir . '/composer.json')) {
            $this->exec($dir, $home);
        }
    }
}
