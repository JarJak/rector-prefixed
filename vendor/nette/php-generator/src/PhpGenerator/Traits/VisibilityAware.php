<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Nette\PhpGenerator\Traits;

use _PhpScoper26e51eeacccf\Nette;
use _PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType;
/**
 * @internal
 */
trait VisibilityAware
{
    /** @var string|null  public|protected|private */
    private $visibility;
    /**
     * @param  string|null  $val  public|protected|private
     * @return static
     */
    public function setVisibility(?string $val) : self
    {
        if (!\in_array($val, [\_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PUBLIC, \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PROTECTED, \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PRIVATE, null], \true)) {
            throw new \_PhpScoper26e51eeacccf\Nette\InvalidArgumentException('Argument must be public|protected|private.');
        }
        $this->visibility = $val;
        return $this;
    }
    public function getVisibility() : ?string
    {
        return $this->visibility;
    }
    /** @return static */
    public function setPublic() : self
    {
        $this->visibility = \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PUBLIC;
        return $this;
    }
    public function isPublic() : bool
    {
        return $this->visibility === \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PUBLIC || $this->visibility === null;
    }
    /** @return static */
    public function setProtected() : self
    {
        $this->visibility = \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PROTECTED;
        return $this;
    }
    public function isProtected() : bool
    {
        return $this->visibility === \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PROTECTED;
    }
    /** @return static */
    public function setPrivate() : self
    {
        $this->visibility = \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PRIVATE;
        return $this;
    }
    public function isPrivate() : bool
    {
        return $this->visibility === \_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType::VISIBILITY_PRIVATE;
    }
}
