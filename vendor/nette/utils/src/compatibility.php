<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace RectorPrefix20201226\Nette\Utils;

if (\false) {
    /** @deprecated use Nette\HtmlStringable */
    interface IHtmlString
    {
    }
} elseif (!\interface_exists(\RectorPrefix20201226\Nette\Utils\IHtmlString::class)) {
    \class_alias(\RectorPrefix20201226\Nette\HtmlStringable::class, \RectorPrefix20201226\Nette\Utils\IHtmlString::class);
}
namespace RectorPrefix20201226\Nette\Localization;

if (\false) {
    /** @deprecated use Nette\Localization\Translator */
    interface ITranslator
    {
    }
} elseif (!\interface_exists(\RectorPrefix20201226\Nette\Localization\ITranslator::class)) {
    \class_alias(\RectorPrefix20201226\Nette\Localization\Translator::class, \RectorPrefix20201226\Nette\Localization\ITranslator::class);
}
