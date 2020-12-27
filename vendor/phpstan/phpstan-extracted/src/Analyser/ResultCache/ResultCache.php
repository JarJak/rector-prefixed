<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Analyser\ResultCache;

use RectorPrefix20201227\PHPStan\Analyser\Error;
use RectorPrefix20201227\PHPStan\Dependency\ExportedNode;
class ResultCache
{
    /** @var bool */
    private $fullAnalysis;
    /** @var string[] */
    private $filesToAnalyse;
    /** @var int */
    private $lastFullAnalysisTime;
    /** @var array<string, array<Error>> */
    private $errors;
    /** @var array<string, array<string>> */
    private $dependencies;
    /** @var array<string, array<ExportedNode>> */
    private $exportedNodes;
    /**
     * @param string[] $filesToAnalyse
     * @param bool $fullAnalysis
     * @param int $lastFullAnalysisTime
     * @param array<string, array<Error>> $errors
     * @param array<string, array<string>> $dependencies
     * @param array<string, array<ExportedNode>> $exportedNodes
     */
    public function __construct(array $filesToAnalyse, bool $fullAnalysis, int $lastFullAnalysisTime, array $errors, array $dependencies, array $exportedNodes)
    {
        $this->filesToAnalyse = $filesToAnalyse;
        $this->fullAnalysis = $fullAnalysis;
        $this->lastFullAnalysisTime = $lastFullAnalysisTime;
        $this->errors = $errors;
        $this->dependencies = $dependencies;
        $this->exportedNodes = $exportedNodes;
    }
    /**
     * @return string[]
     */
    public function getFilesToAnalyse() : array
    {
        return $this->filesToAnalyse;
    }
    public function isFullAnalysis() : bool
    {
        return $this->fullAnalysis;
    }
    public function getLastFullAnalysisTime() : int
    {
        return $this->lastFullAnalysisTime;
    }
    /**
     * @return array<string, array<Error>>
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
    /**
     * @return array<string, array<string>>
     */
    public function getDependencies() : array
    {
        return $this->dependencies;
    }
    /**
     * @return array<string, array<ExportedNode>>
     */
    public function getExportedNodes() : array
    {
        return $this->exportedNodes;
    }
}
