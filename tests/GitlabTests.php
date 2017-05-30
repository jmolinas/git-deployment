<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use JMolinas\GitDeployment\Service\Gitlab;

class GitlabTest extends TestCase
{
    protected $project = [
            'git' => [
                'binary' => '/usr/bin/git'
            ],
            'projects' => [
                'Diaspora' => [
                    'master' => [
                        'name' => 'master',
                        'source' => '/repo/',
                        'deployment' => '/srv/www/awesome'
                    ]
                ],
            ]
        ];

    protected $json;

    protected $request;

    public function setUp()
    {
        $this->json = file_get_contents(__DIR__ . '/_data/gitlab.json');
        $this->request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')->getMock();
        $this->request->method('getContent')->will($this->returnValue($this->json));
    }

    public function testGitlabGetProjectName()
    {
        $gitlab = new Gitlab($this->request, $this->project['projects']);
        $this->assertEquals('Diaspora', $gitlab->project());
    }

    public function testGitlabGetBranchName()
    {
        $gitlab = new Gitlab($this->request, $this->project['projects']);
        $this->assertEquals('master', $gitlab->branch());
    }

    public function testDeploymentConfig()
    {
        $gitlab = new Gitlab($this->request, $this->project['projects']);
        $branch = $this->project['projects'][$gitlab->project()][$gitlab->branch()];
        foreach ($branch as $key => $value) {
            $this->assertEquals($value, $gitlab->{$key}());
        }
    }
}
