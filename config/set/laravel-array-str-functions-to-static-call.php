<?php

declare (strict_types=1);
namespace RectorPrefix20201226;

use Rector\Generic\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use RectorPrefix20201226\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
// @see https://medium.freecodecamp.org/moving-away-from-magic-or-why-i-dont-want-to-use-laravel-anymore-2ce098c979bd
// @see https://laravel.com/docs/5.7/facades#facades-vs-dependency-injection
return static function (\RectorPrefix20201226\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Generic\Rector\FuncCall\FuncCallToStaticCallRector::class)->call('configure', [[\Rector\Generic\Rector\FuncCall\FuncCallToStaticCallRector::FUNC_CALLS_TO_STATIC_CALLS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_add', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'add'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_collapse', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'collapse'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_divide', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'divide'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_dot', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'dot'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_except', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'except'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_first', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'first'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_flatten', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'flatten'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_forget', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'forget'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_get', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'get'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_has', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'has'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_last', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'last'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_only', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'only'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_pluck', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'pluck'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_prepend', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'prepend'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_pull', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'pull'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_random', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'random'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_sort', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'sort'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_sort_recursive', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'sortRecursive'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_where', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'where'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_wrap', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'wrap'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('array_set', 'RectorPrefix20201226\\Illuminate\\Support\\Arr', 'set'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('camel_case', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'camel'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('ends_with', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'endsWith'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('kebab_case', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'kebab'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('snake_case', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'snake'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('starts_with', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'startsWith'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_after', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'after'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_before', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'before'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_contains', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'contains'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_finish', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'finish'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_is', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'is'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_limit', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'limit'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_plural', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'plural'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_random', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'random'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_replace_array', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'replaceArray'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_replace_first', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'replaceFirst'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_replace_last', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'replaceLast'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_singular', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'singular'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_slug', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'slug'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('str_start', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'start'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('studly_case', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'studly'), new \Rector\Transform\ValueObject\FuncCallToStaticCall('title_case', 'RectorPrefix20201226\\Illuminate\\Support\\Str', 'title')])]]);
};
