<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05\OndraM\CiDetector\Ci;

use _PhpScoperabd03f0baf05\OndraM\CiDetector\CiDetector;
use _PhpScoperabd03f0baf05\OndraM\CiDetector\Env;
use _PhpScoperabd03f0baf05\OndraM\CiDetector\TrinaryLogic;
class Jenkins extends \_PhpScoperabd03f0baf05\OndraM\CiDetector\Ci\AbstractCi
{
    public static function isDetected(\_PhpScoperabd03f0baf05\OndraM\CiDetector\Env $env) : bool
    {
        return $env->get('JENKINS_URL') !== \false;
    }
    public function getCiName() : string
    {
        return \_PhpScoperabd03f0baf05\OndraM\CiDetector\CiDetector::CI_JENKINS;
    }
    public function isPullRequest() : \_PhpScoperabd03f0baf05\OndraM\CiDetector\TrinaryLogic
    {
        return \_PhpScoperabd03f0baf05\OndraM\CiDetector\TrinaryLogic::createMaybe();
    }
    public function getBuildNumber() : string
    {
        return $this->env->getString('BUILD_NUMBER');
    }
    public function getBuildUrl() : string
    {
        return $this->env->getString('BUILD_URL');
    }
    public function getGitCommit() : string
    {
        return $this->env->getString('GIT_COMMIT');
    }
    public function getGitBranch() : string
    {
        return $this->env->getString('GIT_BRANCH');
    }
    public function getRepositoryName() : string
    {
        return '';
        // unsupported
    }
    public function getRepositoryUrl() : string
    {
        return $this->env->getString('GIT_URL');
    }
}
