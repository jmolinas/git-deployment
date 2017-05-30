<?php

namespace JMolinas\GitDeployment\Service;

use JMolinas\GitDeployment\Git;
use JMolinas\GitDeployment\Composer;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractService
{
    /**
     * Contains array of permitted branch accessible by method using magic method __call
     * @var array
     */
    protected $branch = [];

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

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        $this->buildProject();
        if (! array_key_exists($method, $this->branch)) {
            throw new \Exception('Undefined method ' . get_class($this) . '::' . $method . '() called');
        }

        return $this->branch[$method];
    }

    private function getBranch($project)
    {

        if (! array_key_exists($this->branch(), $project)) {
            throw new \Exception('Branch Not Exist');
        }
        return $project[$this->branch()];
    }

    protected function buildProject()
    {
        if (! array_key_exists($this->project(), $this->projects)) {
            throw new \Exception('Project Not Exist');
        }
        $this->branch = $this->getBranch($this->projects[$this->project()]);
    }


    public function deploy()
    {
        $git = $this->binary ? new Git($this, $this->binary) : new Git($this);
        $git->pull();
    }

    public function runComposer()
    {
        $composer = new Composer;
        if (is_array($this->deployment())) {
            foreach ($this->deployment() as $deploy) {
                $composer->run($deploy, $this->source());
            }
        } else {
            $composer->run($this->deployment(), $this->source());
        }
    }
}
