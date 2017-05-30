<?php
namespace JMolinas\GitDeployment\Service;

class Bitbucket extends AbstractService implements GitInterface
{
    const BITBUCKET_URI = 'git@bitbucket.org';

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
