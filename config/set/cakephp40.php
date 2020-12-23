<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa;

use _PhpScoper0a2ac50786fa\PHPStan\Type\BooleanType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\NullType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\StringType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\UnionType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\VoidType;
use _PhpScoper0a2ac50786fa\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector;
use _PhpScoper0a2ac50786fa\Rector\CakePHP\Rector\MethodCall\RenameMethodCallBasedOnParameterRector;
use _PhpScoper0a2ac50786fa\Rector\CakePHP\ValueObject\ModalToGetSet;
use _PhpScoper0a2ac50786fa\Rector\CakePHP\ValueObject\RenameMethodCallBasedOnParameter;
use _PhpScoper0a2ac50786fa\Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector;
use _PhpScoper0a2ac50786fa\Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use _PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Name\RenameClassRector;
use _PhpScoper0a2ac50786fa\Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use _PhpScoper0a2ac50786fa\Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use _PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename;
use _PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameClassConstant;
use _PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameProperty;
use _PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameStaticMethod;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use _PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner;
# source: https://book.cakephp.org/4/en/appendices/4-0-migration-guide.html
return static function (\_PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper0a2ac50786fa\\Cake\\Database\\Type' => '_PhpScoper0a2ac50786fa\\Cake\\Database\\TypeFactory', '_PhpScoper0a2ac50786fa\\Cake\\Console\\ConsoleErrorHandler' => '_PhpScoper0a2ac50786fa\\Cake\\Error\\ConsoleErrorHandler']]]);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector::CLASS_CONSTANT_RENAME => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameClassConstant('_PhpScoper0a2ac50786fa\\Cake\\View\\View', 'NAME_ELEMENT', 'TYPE_ELEMENT'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameClassConstant('_PhpScoper0a2ac50786fa\\Cake\\View\\View', 'NAME_LAYOUT', 'TYPE_LAYOUT'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameClassConstant('_PhpScoper0a2ac50786fa\\Cake\\Mailer\\Email', 'MESSAGE_HTML', 'Cake\\Mailer\\Message::MESSAGE_HTML'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameClassConstant('_PhpScoper0a2ac50786fa\\Cake\\Mailer\\Email', 'MESSAGE_TEXT', 'Cake\\Mailer\\Message::MESSAGE_TEXT'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameClassConstant('_PhpScoper0a2ac50786fa\\Cake\\Mailer\\Email', 'MESSAGE_BOTH', 'Cake\\Mailer\\Message::MESSAGE_BOTH'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameClassConstant('_PhpScoper0a2ac50786fa\\Cake\\Mailer\\Email', 'EMAIL_PATTERN', 'Cake\\Mailer\\Message::EMAIL_PATTERN')])]]);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper0a2ac50786fa\\Cake\\Form\\Form', 'errors', 'getErrors'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper0a2ac50786fa\\Cake\\Mailer\\Email', 'set', 'setViewVars'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper0a2ac50786fa\\Cake\\ORM\\EntityInterface', 'unsetProperty', 'unset'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper0a2ac50786fa\\Cake\\Cache\\Cache', 'engine', 'pool'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper0a2ac50786fa\\Cake\\Http\\Cookie\\Cookie', 'getStringValue', 'getScalarValue'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper0a2ac50786fa\\Cake\\Validation\\Validator', 'containsNonAlphaNumeric', 'notAlphaNumeric'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper0a2ac50786fa\\Cake\\Validation\\Validator', 'errors', 'validate')])]]);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameStaticMethod('Router', 'pushRequest', 'Router', 'setRequest'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameStaticMethod('Router', 'setRequestInfo', 'Router', 'setRequest'), new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameStaticMethod('Router', 'setRequestContext', 'Router', 'setRequest')])]]);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector::RENAMED_PROPERTIES => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\Renaming\ValueObject\RenameProperty('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Entity', '_properties', '_fields')])]]);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector::METHOD_RETURN_TYPES => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Http\\BaseApplication', 'bootstrap', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Http\\BaseApplication', 'bootstrapCli', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Http\\BaseApplication', 'middleware', new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType('_PhpScoper0a2ac50786fa\\Cake\\Http\\MiddlewareQueue')), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Console\\Shell', 'initialize', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Component', 'initialize', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Controller', 'initialize', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Controller', 'render', new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType('_PhpScoper0a2ac50786fa\\Cake\\Http\\Response')), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Form\\Form', 'validate', new \_PhpScoper0a2ac50786fa\PHPStan\Type\BooleanType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Form\\Form', '_buildSchema', new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType('_PhpScoper0a2ac50786fa\\Cake\\Form\\Schema')), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'initialize', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'initialize', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'updateAll', new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'deleteAll', new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType()), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'validationDefault', new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType('_PhpScoper0a2ac50786fa\\Cake\\Validation\\Validator')), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'buildRules', new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType('_PhpScoper0a2ac50786fa\\Cake\\ORM\\RulesChecker')), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\View\\Helper', 'initialize', new \_PhpScoper0a2ac50786fa\PHPStan\Type\VoidType())])]]);
    $eventInterfaceObjectType = new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType('_PhpScoper0a2ac50786fa\\Cake\\Event\\EventInterface');
    $services->set(\_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector::PARAMETER_TYPEHINTS => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Form\\Form', 'getData', 0, new \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType([new \_PhpScoper0a2ac50786fa\PHPStan\Type\StringType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\NullType()])), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'beforeFind', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'buildValidator', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'buildRules', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'beforeRules', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'afterRules', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'beforeSave', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'afterSave', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'beforeDelete', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Behavior', 'afterDelete', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'beforeFind', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'buildValidator', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'buildRules', 0, new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType('_PhpScoper0a2ac50786fa\\Cake\\ORM\\RulesChecker')), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'beforeRules', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'afterRules', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'beforeSave', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'afterSave', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'beforeDelete', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\ORM\\Table', 'afterDelete', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Controller', 'beforeFilter', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Controller', 'afterFilter', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Controller', 'beforeRender', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Controller', 'beforeRedirect', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Component', 'shutdown', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Component', 'startup', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Component', 'beforeFilter', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Component', 'beforeRender', 0, $eventInterfaceObjectType), new \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration('_PhpScoper0a2ac50786fa\\Cake\\Controller\\Component', 'beforeRedirect', 0, $eventInterfaceObjectType)])]]);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\CakePHP\Rector\MethodCall\RenameMethodCallBasedOnParameterRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\CakePHP\Rector\MethodCall\RenameMethodCallBasedOnParameterRector::CALLS_WITH_PARAM_RENAMES => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\CakePHP\ValueObject\RenameMethodCallBasedOnParameter('_PhpScoper0a2ac50786fa\\Cake\\Http\\ServerRequest', 'getParam', 'paging', 'getAttribute'), new \_PhpScoper0a2ac50786fa\Rector\CakePHP\ValueObject\RenameMethodCallBasedOnParameter('_PhpScoper0a2ac50786fa\\Cake\\Http\\ServerRequest', 'withParam', 'paging', 'withAttribute')])]]);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\CakePHP\Rector\MethodCall\ModalToGetSetRector::UNPREFIXED_METHODS_TO_GET_SET => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoper0a2ac50786fa\Rector\CakePHP\ValueObject\ModalToGetSet('_PhpScoper0a2ac50786fa\\Cake\\Console\\ConsoleIo', 'styles', 'setStyle', 'getStyle'), new \_PhpScoper0a2ac50786fa\Rector\CakePHP\ValueObject\ModalToGetSet('_PhpScoper0a2ac50786fa\\Cake\\Console\\ConsoleOutput', 'styles', 'setStyle', 'getStyle'), new \_PhpScoper0a2ac50786fa\Rector\CakePHP\ValueObject\ModalToGetSet('_PhpScoper0a2ac50786fa\\Cake\\ORM\\EntityInterface', 'isNew', 'setNew', 'isNew')])]]);
};
