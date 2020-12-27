<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Node;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeAbstract;
use RectorPrefix20201227\PHPStan\Analyser\StatementResult;
class MethodReturnStatementsNode extends \PhpParser\NodeAbstract implements \RectorPrefix20201227\PHPStan\Node\ReturnStatementsNode
{
    /** @var ClassMethod */
    private $classMethod;
    /** @var \PHPStan\Node\ReturnStatement[] */
    private $returnStatements;
    /** @var StatementResult */
    private $statementResult;
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $method
     * @param \PHPStan\Node\ReturnStatement[] $returnStatements
     * @param \PHPStan\Analyser\StatementResult $statementResult
     */
    public function __construct(\PhpParser\Node\Stmt\ClassMethod $method, array $returnStatements, \RectorPrefix20201227\PHPStan\Analyser\StatementResult $statementResult)
    {
        parent::__construct($method->getAttributes());
        $this->classMethod = $method;
        $this->returnStatements = $returnStatements;
        $this->statementResult = $statementResult;
    }
    /**
     * @return \PHPStan\Node\ReturnStatement[]
     */
    public function getReturnStatements() : array
    {
        return $this->returnStatements;
    }
    public function getStatementResult() : \RectorPrefix20201227\PHPStan\Analyser\StatementResult
    {
        return $this->statementResult;
    }
    public function returnsByRef() : bool
    {
        return $this->classMethod->byRef;
    }
    public function getType() : string
    {
        return 'PHPStan_Node_FunctionReturnStatementsNode';
    }
    /**
     * @return string[]
     */
    public function getSubNodeNames() : array
    {
        return [];
    }
}
