<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\OndraM\CiDetector\Ci;

use _PhpScoper26e51eeacccf\OndraM\CiDetector\CiDetector;
use _PhpScoper26e51eeacccf\OndraM\CiDetector\Env;
use _PhpScoper26e51eeacccf\OndraM\CiDetector\TrinaryLogic;
class Bamboo extends \_PhpScoper26e51eeacccf\OndraM\CiDetector\Ci\AbstractCi
{
    public static function isDetected(\_PhpScoper26e51eeacccf\OndraM\CiDetector\Env $env) : bool
    {
        return $env->get('bamboo_buildKey') !== \false;
    }
    public function getCiName() : string
    {
        return \_PhpScoper26e51eeacccf\OndraM\CiDetector\CiDetector::CI_BAMBOO;
    }
    public function isPullRequest() : \_PhpScoper26e51eeacccf\OndraM\CiDetector\TrinaryLogic
    {
        return \_PhpScoper26e51eeacccf\OndraM\CiDetector\TrinaryLogic::createFromBoolean($this->env->get('bamboo_repository_pr_key') !== \false);
    }
    public function getBuildNumber() : string
    {
        return $this->env->getString('bamboo_buildNumber');
    }
    public function getBuildUrl() : string
    {
        return $this->env->getString('bamboo_resultsUrl');
    }
    public function getGitCommit() : string
    {
        return $this->env->getString('bamboo_planRepository_revision');
    }
    public function getGitBranch() : string
    {
        $prBranch = $this->env->getString('bamboo_repository_pr_sourceBranch');
        if ($this->isPullRequest()->no() || empty($prBranch)) {
            return $this->env->getString('bamboo_planRepository_branch');
        }
        return $prBranch;
    }
    public function getRepositoryName() : string
    {
        return $this->env->getString('bamboo_planRepository_name');
    }
    public function getRepositoryUrl() : string
    {
        return $this->env->getString('bamboo_planRepository_repositoryUrl');
    }
}
