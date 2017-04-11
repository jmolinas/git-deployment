# Git-Deployment
Git Deployment Script Helper Class supports Gitlab and BitBucket
use for deployment using webhooks
```
config = [
    'key' => '<--- Secret Key --->',
    'git' => [
        'binary' => '/usr/bin/git',
    ],
    'projects' = [
        'project.name' => [
            'branch.name' => [
                'name' => '<--- Branch Name ---->'
                'deployment' => '<--- Your Web Root --->',
                'source' => '<--- Dir where repo is saved in server --->',
                'binary' => '<--- Git executable binary --->'
            ],
        ],
    ],
]
```
