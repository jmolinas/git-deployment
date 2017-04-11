<?php

namespace JMolinas\GitDeployment\Service;

use Symfony\Component\HttpFoundation\Request;

class Bitbucket extends AbstractService implements GitInterface
{
    const BITBUCKET_URI = 'git@bitbucket.org';
    protected $payload;
    protected $binary;
    protected $projects;

    public function __construct(Request $request, array $projects, $binary = '')
    {
        $this->payload = json_decode($request->getContent());
        if (empty($this->payload)) {
            throw new \Exception('Request payload empty');
        }
        $this->projects = $projects;
    }

    public function branch()
    {
        return $this->payload->push->changes[0]->new->name;
    }

    public function project()
    {
        return $this->payload->repository->name;
    }

    public function remote()
    {
        return self::BITBUCKET_URI . ':' .$this->payload->repository->full_name . '.git';
    }
}
