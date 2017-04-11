<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use JMolinas\GitDeployment\Service\Bitbucket;

class GitlabTest extends TestCase
{
    const JSON = <<< JSON
    {
        "actor": {
            "type": "user",
            "username": "emmap1",
            "display_name": "Emma",
            "uuid": "{a54f16da-24e9-4d7f-a3a7-b1ba2cd98aa3}",
            "links": {
                "self": {
                    "href": "https://api.bitbucket.org/api/2.0/users/emmap1"
                },
                "html": {
                    "href": "https://api.bitbucket.org/emmap1"
                },
                "avatar": {
                    "href": "https://bitbucket-api-assetroot.s3.amazonaws.com/c/photos/2015/Feb/26/3613917261-0-emmap1-avatar_avatar.png"
                }
            }
        },
        "repository": {
            "type": "repository",
            "links": {
                "self": {
                    "href": "https://api.bitbucket.org/api/2.0/repositories/bitbucket/bitbucket"
                },
                "html": {
                    "href": "https://api.bitbucket.org/bitbucket/bitbucket"
                },
                "avatar": {
                    "href": "https://api-staging-assetroot.s3.amazonaws.com/c/photos/2014/Aug/01/bitbucket-logo-2629490769-3_avatar.png"
                }
            },
            "uuid": "{673a6070-3421-46c9-9d48-90745f7bfe8e}",
            "project": {
                "type": "project",
                "project": "Untitled project",
                "uuid": "{3b7898dc-6891-4225-ae60-24613bb83080}",
                "links": {
                    "html": {
                        "href": "https://bitbucket.org/account/user/teamawesome/projects/proj"
                    },
                    "avatar": {
                        "href": "https://bitbucket.org/account/user/teamawesome/projects/proj/avatar/32"
                    }
                },
                "key": "proj"
            },
            "full_name": "team_name/repo_name",
            "name": "repo_name",
            "website": "https://mywebsite.com/",
            "owner": {
                "type": "user",
                "username": "emmap1",
                "display_name": "Emma",
                "uuid": "{a54f16da-24e9-4d7f-a3a7-b1ba2cd98aa3}",
                "links": {
                    "self": {
                        "href": "https://api.bitbucket.org/api/2.0/users/emmap1"
                    },
                    "html": {
                        "href": "https://api.bitbucket.org/emmap1"
                    },
                    "avatar": {
                        "href": "https://bitbucket-api-assetroot.s3.amazonaws.com/c/photos/2015/Feb/26/3613917261-0-emmap1-avatar_avatar.png"
                    }
                }
            },
            "scm": "git",
            "is_private": true
        },
        "push": {
            "changes": [
                {
                    "new": {
                        "type": "branch",
                        "name": "name-of-branch"
                    }
                }
            ]
        }
    }
JSON;

    const PROJECT = [
            'key' => '9d48A90745f7bfe8e',
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

    public function testBitbucketGetProjectName()
    {
        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')->getMock();
        $request->method('getContent')->will($this->returnValue(self::JSON));
        $bitbucket = new Bitbucket($request, self::PROJECT['projects']);
        $this->assertEquals('repo_name', $bitbucket->project());
    }

    public function testBitbucketGetBranchName()
    {
        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')->getMock();
        $request->method('getContent')->will($this->returnValue(self::JSON));
        $bitbucket = new Bitbucket($request, self::PROJECT['projects']);
        $this->assertEquals('name-of-branch', $bitbucket->branch());
    }

    public function testBitbucketDeploymentConfig()
    {
        $request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')->getMock();
        $request->method('getContent')->will($this->returnValue(self::JSON));
        $bitbucket = new Bitbucket($request, self::PROJECT['projects']);
        $branch = self::PROJECT['projects'][$bitbucket->project()][$bitbucket->branch()];
        foreach ($branch as $key => $value) {
            $this->assertEquals($value, $bitbucket->{$key}());
        }
    }
}
