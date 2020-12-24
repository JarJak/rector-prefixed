<?php

declare (strict_types=1);
namespace _PhpScoper2a4e7ab1ecbc\Rector\CodingStyle\Application;

use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt;
use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\Namespace_;
use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\Use_;
final class UseImportsRemover
{
    /**
     * @param Stmt[] $stmts
     * @param string[] $removedShortUses
     * @return Stmt[]
     */
    public function removeImportsFromStmts(array $stmts, array $removedShortUses) : array
    {
        foreach ($stmts as $stmtKey => $stmt) {
            if (!$stmt instanceof \_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\Use_) {
                continue;
            }
            $this->removeUseFromUse($removedShortUses, $stmt);
            // nothing left → remove
            if ($stmt->uses === []) {
                unset($stmts[$stmtKey]);
            }
        }
        return $stmts;
    }
    /**
     * @param string[] $removedShortUses
     */
    public function removeImportsFromNamespace(\_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\Namespace_ $namespace, array $removedShortUses) : void
    {
        foreach ($namespace->stmts as $namespaceKey => $stmt) {
            if (!$stmt instanceof \_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\Use_) {
                continue;
            }
            $this->removeUseFromUse($removedShortUses, $stmt);
            // nothing left → remove
            if ($stmt->uses === []) {
                unset($namespace->stmts[$namespaceKey]);
            }
        }
    }
    /**
     * @param string[] $removedShortUses
     */
    private function removeUseFromUse(array $removedShortUses, \_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\Use_ $use) : void
    {
        foreach ($use->uses as $usesKey => $useUse) {
            foreach ($removedShortUses as $removedShortUse) {
                if ($useUse->name->getLast() !== $removedShortUse) {
                    continue;
                }
                unset($use->uses[$usesKey]);
            }
        }
    }
}
