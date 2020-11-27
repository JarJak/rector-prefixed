<?php

namespace _PhpScoperbd5d0c5f7638\Bug2001;

use function PHPStan\Analyser\assertType;
class HelloWorld
{
    public function parseUrl(string $url) : string
    {
        $parsedUrl = \parse_url(\urldecode($url));
        \PHPStan\Analyser\assertType('array(?\'scheme\' => string, ?\'host\' => string, ?\'port\' => int, ?\'user\' => string, ?\'pass\' => string, ?\'path\' => string, ?\'query\' => string, ?\'fragment\' => string)|false', $parsedUrl);
        if (\array_key_exists('host', $parsedUrl)) {
            \PHPStan\Analyser\assertType('array(?\'scheme\' => string, \'host\' => string, ?\'port\' => int, ?\'user\' => string, ?\'pass\' => string, ?\'path\' => string, ?\'query\' => string, ?\'fragment\' => string)', $parsedUrl);
            throw new \RuntimeException('Absolute URLs are prohibited for the redirectTo parameter.');
        }
        \PHPStan\Analyser\assertType('array(?\'scheme\' => string, ?\'port\' => int, ?\'user\' => string, ?\'pass\' => string, ?\'path\' => string, ?\'query\' => string, ?\'fragment\' => string)|false', $parsedUrl);
        $redirectUrl = $parsedUrl['path'];
        if (\array_key_exists('query', $parsedUrl)) {
            \PHPStan\Analyser\assertType('array(?\'scheme\' => string, ?\'port\' => int, ?\'user\' => string, ?\'pass\' => string, ?\'path\' => string, \'query\' => string, ?\'fragment\' => string)', $parsedUrl);
            $redirectUrl .= '?' . $parsedUrl['query'];
        }
        if (\array_key_exists('fragment', $parsedUrl)) {
            \PHPStan\Analyser\assertType('array(?\'scheme\' => string, ?\'port\' => int, ?\'user\' => string, ?\'pass\' => string, ?\'path\' => string, ?\'query\' => string, \'fragment\' => string)', $parsedUrl);
            $redirectUrl .= '#' . $parsedUrl['query'];
        }
        \PHPStan\Analyser\assertType('array(?\'scheme\' => string, ?\'port\' => int, ?\'user\' => string, ?\'pass\' => string, ?\'path\' => string, ?\'query\' => string, ?\'fragment\' => string)|false', $parsedUrl);
        return $redirectUrl;
    }
    public function doFoo(int $i)
    {
        $a = ['a' => $i];
        if (\rand(0, 1)) {
            $a['b'] = $i;
        }
        if (\rand(0, 1)) {
            $a = ['d' => $i];
        }
        \PHPStan\Analyser\assertType('array(\'a\' => int, ?\'b\' => int)|array(\'d\' => int)', $a);
    }
}
