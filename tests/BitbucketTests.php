<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use JMolinas\GitDeployment\Service\Bitbucket;

class GitlabTest extends TestCase
{
    protected $project = [
            'git' => [
                'binary' => '/usr/bin/git'
            ],
            'projects' => [
                'repo_name' => [
                    'name-of-branch' => [
                        'name' => 'name-of-branch',
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
        $this->json = file_get_contents(__DIR__ . '/_data/bitbucket.json');
        $this->request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')->getMock();
        $this->request->method('getContent')->will($this->returnValue($this->json));
    }

    public function testBitbucketGetProjectName()
    {
        $bitbucket = new Bitbucket($this->request, $this->project['projects']);
        $this->assertEquals('repo_name', $bitbucket->project());
    }

    public function testBitbucketGetBranchName()
    {
        $bitbucket = new Bitbucket($this->request, $this->project['projects']);
        $this->assertEquals('name-of-branch', $bitbucket->branch());
    }

    public function testBitbucketDeploymentConfig()
    {
        $bitbucket = new Bitbucket($this->request, $this->project['projects']);
        $branch = $this->project['projects'][$bitbucket->project()][$bitbucket->branch()];
        foreach ($branch as $key => $value) {
            $this->assertEquals($value, $bitbucket->{$key}());
        }
    }
}
