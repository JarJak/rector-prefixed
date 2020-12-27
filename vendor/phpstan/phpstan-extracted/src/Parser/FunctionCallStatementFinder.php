<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Parser;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
class FunctionCallStatementFinder
{
    /**
     * @param string[] $functionNames
     * @param mixed $statements
     * @return \PhpParser\Node|null
     */
    public function findFunctionCallInStatements(array $functionNames, $statements) : ?\PhpParser\Node
    {
        foreach ($statements as $statement) {
            if (\is_array($statement)) {
                $result = $this->findFunctionCallInStatements($functionNames, $statement);
                if ($result !== null) {
                    return $result;
                }
            }
            if (!$statement instanceof \PhpParser\Node) {
                continue;
            }
            if ($statement instanceof \PhpParser\Node\Expr\FuncCall && $statement->name instanceof \PhpParser\Node\Name) {
                if (\in_array((string) $statement->name, $functionNames, \true)) {
                    return $statement;
                }
            }
            $result = $this->findFunctionCallInStatements($functionNames, $statement);
            if ($result !== null) {
                return $result;
            }
        }
        return null;
    }
}
