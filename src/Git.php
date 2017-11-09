<?php
namespace JMolinas\GitDeployment;

use Symfony\Component\Process\Process;
use JMolinas\GitDeployment\Service\GitInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Git
{
    protected $source;
    protected $binary;
    protected $branch;


    public function __construct(GitInterface $branch, $binary = '/usr/bin/git')
    {
        $this->branch = $branch;
        $this->source = $this->branch->source() . $this->branch->project() . ".git";
        $this->binary = $binary;
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    private function fetch()
    {
        $command = $this->binary  . ' fetch --all --tags';
        $process = new Process($command, $this->source);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    private function gitClone()
    {
        $command = $this->binary  . ' clone --mirror ' . $this->branch->remote();
        $process = new Process($command, $this->branch->source());
        $process->run();
    }

    private function deploy($worktree)
    {
        $command = $this->binary . ' --git-dir=' . $this->source . ' --work-tree=' . $worktree . ' checkout -f ' . $this->branch->name();
        $process = new Process($command);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    public function pull()
    {
        $this->gitClone();
        $this->fetch();
        if (is_array($this->branch->deployment())) {
            foreach ($this->branch->deployment() as $dir) {
                $this->checkDirExist($dir);
                $this->deploy($dir);
            }
        } else {
            $this->checkDirExist($this->branch->deployment());
            $this->deploy($this->branch->deployment());
        }

        return true;
    }

    private function checkDirExist($dir)
    {
        if (! is_dir($dir)) {
            throw new \Exception("Deployment dir not exist");
        }
    }
}
