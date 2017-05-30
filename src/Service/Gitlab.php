<?php
namespace JMolinas\GitDeployment\Service;

class Gitlab extends AbstractService implements GitInterface
{
    public function branch()
    {
        $ref = explode('/', $this->payload->ref);
        return end($ref);
    }

    public function project()
    {
        return  $this->payload->project->name;
    }

    public function remote()
    {
        $this->payload->project->git_ssh_url;
    }
}
