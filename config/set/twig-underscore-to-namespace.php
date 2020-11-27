<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf;

use Rector\Generic\ValueObject\PseudoNamespaceToNamespace;
use Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['Twig_LoaderInterface' => '_PhpScoper26e51eeacccf\\Twig\\Loader\\LoaderInterface', 'Twig_Extension_StringLoader' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\StringLoaderExtension', 'Twig_Extension_Optimizer' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\OptimizerExtension', 'Twig_Extension_Debug' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\DebugExtension', 'Twig_Extension_Sandbox' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\SandboxExtension', 'Twig_Extension_Profiler' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\ProfilerExtension', 'Twig_Extension_Escaper' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\EscaperExtension', 'Twig_Extension_Staging' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\StagingExtension', 'Twig_Extension_Core' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\CoreExtension', 'Twig_Node' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Node', 'Twig_NodeVisitor_Optimizer' => '_PhpScoper26e51eeacccf\\Twig\\NodeVisitor\\OptimizerNodeVisitor', 'Twig_NodeVisitor_SafeAnalysis' => '_PhpScoper26e51eeacccf\\Twig\\NodeVisitor\\SafeAnalysisNodeVisitor', 'Twig_NodeVisitor_Sandbox' => '_PhpScoper26e51eeacccf\\Twig\\NodeVisitor\\SandboxNodeVisitor', 'Twig_NodeVisitor_Escaper' => '_PhpScoper26e51eeacccf\\Twig\\NodeVisitor\\EscaperNodeVisitor', 'Twig_SimpleFunction' => '_PhpScoper26e51eeacccf\\Twig\\TwigFunction', 'Twig_Function' => '_PhpScoper26e51eeacccf\\Twig\\TwigFunction', 'Twig_Error_Syntax' => '_PhpScoper26e51eeacccf\\Twig\\Error\\SyntaxError', 'Twig_Error_Loader' => '_PhpScoper26e51eeacccf\\Twig\\Error\\LoaderError', 'Twig_Error_Runtime' => '_PhpScoper26e51eeacccf\\Twig\\Error\\RuntimeError', 'Twig_TokenParser' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\AbstractTokenParser', 'Twig_TokenParserInterface' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\TokenParserInterface', 'Twig_CacheInterface' => '_PhpScoper26e51eeacccf\\Twig\\Cache\\CacheInterface', 'Twig_NodeVisitorInterface' => '_PhpScoper26e51eeacccf\\Twig\\NodeVisitor\\NodeVisitorInterface', 'Twig_Profiler_NodeVisitor_Profiler' => '_PhpScoper26e51eeacccf\\Twig\\Profiler\\NodeVisitor\\ProfilerNodeVisitor', 'Twig_Profiler_Dumper_Text' => '_PhpScoper26e51eeacccf\\Twig\\Profiler\\Dumper\\TextDumper', 'Twig_Profiler_Dumper_Base' => '_PhpScoper26e51eeacccf\\Twig\\Profiler\\Dumper\\BaseDumper', 'Twig_Profiler_Dumper_Blackfire' => '_PhpScoper26e51eeacccf\\Twig\\Profiler\\Dumper\\BlackfireDumper', 'Twig_Profiler_Dumper_Html' => '_PhpScoper26e51eeacccf\\Twig\\Profiler\\Dumper\\HtmlDumper', 'Twig_Profiler_Node_LeaveProfile' => '_PhpScoper26e51eeacccf\\Twig\\Profiler\\Node\\LeaveProfileNode', 'Twig_Profiler_Node_EnterProfile' => '_PhpScoper26e51eeacccf\\Twig\\Profiler\\Node\\EnterProfileNode', 'Twig_Error' => '_PhpScoper26e51eeacccf\\Twig\\Error\\Error', 'Twig_ExistsLoaderInterface' => '_PhpScoper26e51eeacccf\\Twig\\Loader\\ExistsLoaderInterface', 'Twig_SimpleTest' => '_PhpScoper26e51eeacccf\\Twig\\TwigTest', 'Twig_Test' => '_PhpScoper26e51eeacccf\\Twig\\TwigTest', 'Twig_FactoryRuntimeLoader' => '_PhpScoper26e51eeacccf\\Twig\\RuntimeLoader\\FactoryRuntimeLoader', 'Twig_NodeOutputInterface' => '_PhpScoper26e51eeacccf\\Twig\\Node\\NodeOutputInterface', 'Twig_SimpleFilter' => '_PhpScoper26e51eeacccf\\Twig\\TwigFilter', 'Twig_Filter' => '_PhpScoper26e51eeacccf\\Twig\\TwigFilter', 'Twig_Loader_Chain' => '_PhpScoper26e51eeacccf\\Twig\\Loader\\ChainLoader', 'Twig_Loader_Array' => '_PhpScoper26e51eeacccf\\Twig\\Loader\\ArrayLoader', 'Twig_Loader_Filesystem' => '_PhpScoper26e51eeacccf\\Twig\\Loader\\FilesystemLoader', 'Twig_Cache_Null' => '_PhpScoper26e51eeacccf\\Twig\\Cache\\NullCache', 'Twig_Cache_Filesystem' => '_PhpScoper26e51eeacccf\\Twig\\Cache\\FilesystemCache', 'Twig_NodeCaptureInterface' => '_PhpScoper26e51eeacccf\\Twig\\Node\\NodeCaptureInterface', 'Twig_Extension' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\AbstractExtension', 'Twig_TokenParser_Macro' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\MacroTokenParser', 'Twig_TokenParser_Embed' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\EmbedTokenParser', 'Twig_TokenParser_Do' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\DoTokenParser', 'Twig_TokenParser_From' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\FromTokenParser', 'Twig_TokenParser_Extends' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\ExtendsTokenParser', 'Twig_TokenParser_Set' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\SetTokenParser', 'Twig_TokenParser_Sandbox' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\SandboxTokenParser', 'Twig_TokenParser_AutoEscape' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\AutoEscapeTokenParser', 'Twig_TokenParser_With' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\WithTokenParser', 'Twig_TokenParser_Include' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\IncludeTokenParser', 'Twig_TokenParser_Block' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\BlockTokenParser', 'Twig_TokenParser_Filter' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\FilterTokenParser', 'Twig_TokenParser_If' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\IfTokenParser', 'Twig_TokenParser_For' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\ForTokenParser', 'Twig_TokenParser_Flush' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\FlushTokenParser', 'Twig_TokenParser_Spaceless' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\SpacelessTokenParser', 'Twig_TokenParser_Use' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\UseTokenParser', 'Twig_TokenParser_Import' => '_PhpScoper26e51eeacccf\\Twig\\TokenParser\\ImportTokenParser', 'Twig_ContainerRuntimeLoader' => '_PhpScoper26e51eeacccf\\Twig\\RuntimeLoader\\ContainerRuntimeLoader', 'Twig_SourceContextLoaderInterface' => '_PhpScoper26e51eeacccf\\Twig\\Loader\\SourceContextLoaderInterface', 'Twig_NodeTraverser' => '_PhpScoper26e51eeacccf\\Twig\\NodeTraverser', 'Twig_ExtensionInterface' => '_PhpScoper26e51eeacccf\\Twig\\Extension\\ExtensionInterface', 'Twig_Node_Macro' => '_PhpScoper26e51eeacccf\\Twig\\Node\\MacroNode', 'Twig_Node_Embed' => '_PhpScoper26e51eeacccf\\Twig\\Node\\EmbedNode', 'Twig_Node_Do' => '_PhpScoper26e51eeacccf\\Twig\\Node\\DoNode', 'Twig_Node_Text' => '_PhpScoper26e51eeacccf\\Twig\\Node\\TextNode', 'Twig_Node_Set' => '_PhpScoper26e51eeacccf\\Twig\\Node\\SetNode', 'Twig_Node_Sandbox' => '_PhpScoper26e51eeacccf\\Twig\\Node\\SandboxNode', 'Twig_Node_AutoEscape' => '_PhpScoper26e51eeacccf\\Twig\\Node\\AutoEscapeNode', 'Twig_Node_With' => '_PhpScoper26e51eeacccf\\Twig\\Node\\WithNode', 'Twig_Node_Include' => '_PhpScoper26e51eeacccf\\Twig\\Node\\IncludeNode', 'Twig_Node_Print' => '_PhpScoper26e51eeacccf\\Twig\\Node\\PrintNode', 'Twig_Node_Block' => '_PhpScoper26e51eeacccf\\Twig\\Node\\BlockNode', 'Twig_Node_Expression_MethodCall' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\MethodCallExpression', 'Twig_Node_Expression_Unary_Pos' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Unary\\PosUnary', 'Twig_Node_Expression_Unary_Not' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Unary\\NotUnary', 'Twig_Node_Expression_Unary_Neg' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Unary\\NegUnary', 'Twig_Node_Expression_GetAttr' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\GetAttrExpression', 'Twig_Node_Expression_Function' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\FunctionExpression', 'Twig_Node_Expression_Binary_Power' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\PowerBinary', 'Twig_Node_Expression_Binary_In' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\InBinary', 'Twig_Node_Expression_Binary_BitwiseXor' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\BitwiseXorBinary', 'Twig_Node_Expression_Binary_Concat' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\ConcatBinary', 'Twig_Node_Expression_Binary_NotEqual' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\NotEqualBinary', 'Twig_Node_Expression_Binary_Less' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\LessBinary', 'Twig_Node_Expression_Binary_And' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\AndBinary', 'Twig_Node_Expression_Binary_GreaterEqual' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\GreaterEqualBinary', 'Twig_Node_Expression_Binary_Mod' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\ModBinary', 'Twig_Node_Expression_Binary_NotIn' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\NotInBinary', 'Twig_Node_Expression_Binary_Add' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\AddBinary', 'Twig_Node_Expression_Binary_Matches' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\MatchesBinary', 'Twig_Node_Expression_Binary_EndsWith' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\EndsWithBinary', 'Twig_Node_Expression_Binary_FloorDiv' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\FloorDivBinary', 'Twig_Node_Expression_Binary_StartsWith' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\StartsWithBinary', 'Twig_Node_Expression_Binary_LessEqual' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\LessEqualBinary', 'Twig_Node_Expression_Binary_Equal' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\EqualBinary', 'Twig_Node_Expression_Binary_BitwiseAnd' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\BitwiseAndBinary', 'Twig_Node_Expression_Binary_Mul' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\MulBinary', 'Twig_Node_Expression_Binary_Range' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\RangeBinary', 'Twig_Node_Expression_Binary_Or' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\OrBinary', 'Twig_Node_Expression_Binary_Greater' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\GreaterBinary', 'Twig_Node_Expression_Binary_Div' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\DivBinary', 'Twig_Node_Expression_Binary_BitwiseOr' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\BitwiseOrBinary', 'Twig_Node_Expression_Binary_Sub' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\SubBinary', 'Twig_Node_Expression_Test_Even' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Test\\EvenTest', 'Twig_Node_Expression_Test_Defined' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Test\\DefinedTest', 'Twig_Node_Expression_Test_Sameas' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Test\\SameasTest', 'Twig_Node_Expression_Test_Odd' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Test\\OddTest', 'Twig_Node_Expression_Test_Constant' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Test\\ConstantTest', 'Twig_Node_Expression_Test_Null' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Test\\NullTest', 'Twig_Node_Expression_Test_Divisibleby' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Test\\DivisiblebyTest', 'Twig_Node_Expression_Array' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\ArrayExpression', 'Twig_Node_Expression_Binary' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Binary\\AbstractBinary', 'Twig_Node_Expression_Constant' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\ConstantExpression', 'Twig_Node_Expression_Parent' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\ParentExpression', 'Twig_Node_Expression_Test' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\TestExpression', 'Twig_Node_Expression_Filter_Default' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Filter\\DefaultFilter', 'Twig_Node_Expression_Filter' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\FilterExpression', 'Twig_Node_Expression_BlockReference' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\BlockReferenceExpression', 'Twig_Node_Expression_NullCoalesce' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\NullCoalesceExpression', 'Twig_Node_Expression_Name' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\NameExpression', 'Twig_Node_Expression_TempName' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\TempNameExpression', 'Twig_Node_Expression_Call' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\CallExpression', 'Twig_Node_Expression_Unary' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\Unary\\AbstractUnary', 'Twig_Node_Expression_AssignName' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\AssignNameExpression', 'Twig_Node_Expression_Conditional' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\ConditionalExpression', 'Twig_Node_CheckSecurity' => '_PhpScoper26e51eeacccf\\Twig\\Node\\CheckSecurityNode', 'Twig_Node_Expression' => '_PhpScoper26e51eeacccf\\Twig\\Node\\Expression\\AbstractExpression', 'Twig_Node_ForLoop' => '_PhpScoper26e51eeacccf\\Twig\\Node\\ForLoopNode', 'Twig_Node_If' => '_PhpScoper26e51eeacccf\\Twig\\Node\\IfNode', 'Twig_Node_For' => '_PhpScoper26e51eeacccf\\Twig\\Node\\ForNode', 'Twig_Node_BlockReference' => '_PhpScoper26e51eeacccf\\Twig\\Node\\BlockReferenceNode', 'Twig_Node_Flush' => '_PhpScoper26e51eeacccf\\Twig\\Node\\FlushNode', 'Twig_Node_Body' => '_PhpScoper26e51eeacccf\\Twig\\Node\\BodyNode', 'Twig_Node_Spaceless' => '_PhpScoper26e51eeacccf\\Twig\\Node\\SpacelessNode', 'Twig_Node_Import' => '_PhpScoper26e51eeacccf\\Twig\\Node\\ImportNode', 'Twig_Node_SandboxedPrint' => '_PhpScoper26e51eeacccf\\Twig\\Node\\SandboxedPrintNode', 'Twig_Node_Module' => '_PhpScoper26e51eeacccf\\Twig\\Node\\ModuleNode', 'Twig_RuntimeLoaderInterface' => '_PhpScoper26e51eeacccf\\Twig\\RuntimeLoader\\RuntimeLoaderInterface', 'Twig_BaseNodeVisitor' => '_PhpScoper26e51eeacccf\\Twig\\NodeVisitor\\AbstractNodeVisitor', 'Twig_Extensions_Extension_Text' => '_PhpScoper26e51eeacccf\\Twig\\Extensions\\TextExtension', 'Twig_Extensions_Extension_Array' => '_PhpScoper26e51eeacccf\\Twig\\Extensions\\ArrayExtension', 'Twig_Extensions_Extension_Date' => '_PhpScoper26e51eeacccf\\Twig\\Extensions\\DateExtension', 'Twig_Extensions_Extension_I18n' => '_PhpScoper26e51eeacccf\\Twig\\Extensions\\I18nExtension', 'Twig_Extensions_Extension_Intl' => '_PhpScoper26e51eeacccf\\Twig\\Extensions\\IntlExtension', 'Twig_Extensions_TokenParser_Trans' => '_PhpScoper26e51eeacccf\\Twig\\Extensions\\TokenParser\\TransTokenParser', 'Twig_Extensions_Node_Trans' => '_PhpScoper26e51eeacccf\\Twig\\Extensions\\Node\\TransNode']]]);
    $services->set(\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::class)->call('configure', [[\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::NAMESPACE_PREFIXES_WITH_EXCLUDED_CLASSES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\PseudoNamespaceToNamespace('Twig_')])]]);
};
