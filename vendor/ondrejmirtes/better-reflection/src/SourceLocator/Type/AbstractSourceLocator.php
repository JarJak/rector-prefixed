<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05\Roave\BetterReflection\SourceLocator\Type;

use _PhpScoperabd03f0baf05\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflector\Reflector;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\SourceLocator\Ast\Exception\ParseToAstFailure;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\SourceLocator\Ast\Locator as AstLocator;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\SourceLocator\Located\LocatedSource;
abstract class AbstractSourceLocator implements \_PhpScoperabd03f0baf05\Roave\BetterReflection\SourceLocator\Type\SourceLocator
{
    /** @var AstLocator */
    private $astLocator;
    /**
     * Children should implement this method and return a LocatedSource object
     * which contains the source and the file from which it was located.
     *
     * @example
     *   return new LocatedSource(['<?php class Foo {}', null]);
     *   return new LocatedSource([\file_get_contents('Foo.php'), 'Foo.php']);
     */
    protected abstract function createLocatedSource(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScoperabd03f0baf05\Roave\BetterReflection\SourceLocator\Located\LocatedSource;
    public function __construct(\_PhpScoperabd03f0baf05\Roave\BetterReflection\SourceLocator\Ast\Locator $astLocator)
    {
        $this->astLocator = $astLocator;
    }
    /**
     * {@inheritDoc}
     *
     * @throws ParseToAstFailure
     */
    public function locateIdentifier(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoperabd03f0baf05\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\Reflection
    {
        $locatedSource = $this->createLocatedSource($identifier);
        if (!$locatedSource) {
            return null;
        }
        try {
            return $this->astLocator->findReflection($reflector, $locatedSource, $identifier);
        } catch (\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflector\Exception\IdentifierNotFound $exception) {
            return null;
        }
    }
    /**
     * {@inheritDoc}
     *
     * @throws ParseToAstFailure
     */
    public final function locateIdentifiersByType(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoperabd03f0baf05\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array
    {
        $locatedSource = $this->createLocatedSource(new \_PhpScoperabd03f0baf05\Roave\BetterReflection\Identifier\Identifier(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Identifier\Identifier::WILDCARD, $identifierType));
        if (!$locatedSource) {
            return [];
        }
        return $this->astLocator->findReflectionsOfType($reflector, $locatedSource, $identifierType);
    }
}
