<?php

namespace _PhpScoper006a73f0e455\Bug3269;

use function PHPStan\Analyser\assertType;
class Foo
{
    /**
     * @param list<list<array{start: Blah, end: Blah}>> $intervalGroups
     */
    public static function bar(array $intervalGroups) : void
    {
        $borders = [];
        foreach ($intervalGroups as $group) {
            foreach ($group as $interval) {
                $borders[] = ['version' => $interval['start']->getVersion(), 'operator' => $interval['start']->getOperator(), 'side' => 'start'];
                $borders[] = ['version' => $interval['end']->getVersion(), 'operator' => $interval['end']->getOperator(), 'side' => 'end'];
            }
        }
        \PHPStan\Analyser\assertType('array<int, array(\'version\' => string, \'operator\' => string, \'side\' => \'end\'|\'start\')>', $borders);
        foreach ($borders as $border) {
            \PHPStan\Analyser\assertType('array(\'version\' => string, \'operator\' => string, \'side\' => \'end\'|\'start\')', $border);
            \PHPStan\Analyser\assertType('\'end\'|\'start\'', $border['side']);
        }
    }
}
class Blah
{
    public function getVersion() : string
    {
        return '';
    }
    public function getOperator() : string
    {
        return '';
    }
}