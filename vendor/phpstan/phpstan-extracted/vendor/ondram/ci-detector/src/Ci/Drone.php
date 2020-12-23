<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\Ci;

use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\CiDetector;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\Env;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\TrinaryLogic;
class Drone extends \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\Ci\AbstractCi
{
    public static function isDetected(\_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\Env $env) : bool
    {
        return $env->get('CI') === 'drone';
    }
    public function getCiName() : string
    {
        return \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\CiDetector::CI_DRONE;
    }
    public function isPullRequest() : \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\TrinaryLogic
    {
        return \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\OndraM\CiDetector\TrinaryLogic::createFromBoolean($this->env->getString('DRONE_PULL_REQUEST') !== '');
    }
    public function getBuildNumber() : string
    {
        return $this->env->getString('DRONE_BUILD_NUMBER');
    }
    public function getBuildUrl() : string
    {
        return $this->env->getString('DRONE_BUILD_LINK');
    }
    public function getGitCommit() : string
    {
        return $this->env->getString('DRONE_COMMIT_SHA');
    }
    public function getGitBranch() : string
    {
        return $this->env->getString('DRONE_COMMIT_BRANCH');
    }
    public function getRepositoryName() : string
    {
        return $this->env->getString('DRONE_REPO');
    }
    public function getRepositoryUrl() : string
    {
        return $this->env->getString('DRONE_REPO_LINK');
    }
}
