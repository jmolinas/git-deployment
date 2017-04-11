<?php

namespace JMolinas\GitDeployment\Service;

use Symfony\Component\HttpFoundation\Request;

class Gitlab extends AbstractService implements GitInterface
{
    protected $payload;
    protected $projects;

    public function __construct(Request $request, array $projects, $binary = '')
    {
        $this->payload = json_decode($request->getContent());
        if (empty($this->payload)) {
            throw new \Exception('Request payload empty');
        }
        $this->binary = $binary;
        $this->projects = $projects;
    }

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
