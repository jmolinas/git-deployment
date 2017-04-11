<?php

namespace JMolinas\GitDeployment\Service;

interface GitInterface
{
    public function branch();
    public function project();
    public function remote();
}
