<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\PackageVersions;

use _PhpScoper006a73f0e455\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\_PhpScoper006a73f0e455\Composer\InstalledVersions::class);
/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'rector/rector';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS = array('clue/block-react' => 'v1.4.0@c8e7583ae55127b89d6915480ce295bac81c4f88', 'clue/ndjson-react' => 'v1.1.0@767ec9543945802b5766fab0da4520bf20626f66', 'composer/ca-bundle' => '1.2.8@8a7ecad675253e4654ea05505233285377405215', 'composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6', 'composer/xdebug-handler' => '1.4.5@f28d44c286812c714741478d968104c5e604a1d4', 'doctrine/annotations' => '1.11.1@ce77a7ba1770462cd705a91a151b6c3746f9c6ad', 'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210', 'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042', 'evenement/evenement' => 'v3.0.1@531bfb9d15f8aa57454f5f0285b18bec903b8fb7', 'hoa/compiler' => '3.17.08.08@aa09caf0bf28adae6654ca6ee415ee2f522672de', 'hoa/consistency' => '1.17.05.02@fd7d0adc82410507f332516faf655b6ed22e4c2f', 'hoa/event' => '1.17.01.13@6c0060dced212ffa3af0e34bb46624f990b29c54', 'hoa/exception' => '1.17.01.16@091727d46420a3d7468ef0595651488bfc3a458f', 'hoa/file' => '1.17.07.11@35cb979b779bc54918d2f9a4e02ed6c7a1fa67ca', 'hoa/iterator' => '2.17.01.10@d1120ba09cb4ccd049c86d10058ab94af245f0cc', 'hoa/math' => '1.17.05.16@7150785d30f5d565704912116a462e9f5bc83a0c', 'hoa/protocol' => '1.17.01.14@5c2cf972151c45f373230da170ea015deecf19e2', 'hoa/regex' => '1.17.01.13@7e263a61b6fb45c1d03d8e5ef77668518abd5bec', 'hoa/stream' => '1.17.02.21@3293cfffca2de10525df51436adf88a559151d82', 'hoa/ustring' => '4.17.01.16@e6326e2739178799b1fe3fdd92029f9517fa17a0', 'hoa/visitor' => '2.17.01.16@c18fe1cbac98ae449e0d56e87469103ba08f224a', 'hoa/zformat' => '1.17.01.10@522c381a2a075d4b9dbb42eb4592dd09520e4ac2', 'jean85/pretty-package-versions' => '1.5.1@a917488320c20057da87f67d0d40543dd9427f7a', 'jetbrains/phpstorm-stubs' => 'dev-master@05d145c0bbafcf9a551fdd8824adb2a7e259fdaf', 'nette/bootstrap' => 'v3.0.2@67830a65b42abfb906f8e371512d336ebfb5da93', 'nette/di' => 'v3.0.6@e639ccfbc0230e022ca08bf59c6b07df7caf2007', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.1@a5b3a60833d2ef55283a82d0c30b45d136b29e75', 'nette/php-generator' => 'v3.5.1@fe54415cd22d01bee1307a608058bf131978610a', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/schema' => 'v1.0.3@34baf9eca75eccdad3d04306c5d6bec0f6b252ad', 'nette/utils' => 'v3.2.0@d0427c1811462dbb6c503143eabe5478b26685f7', 'nikic/php-parser' => 'v4.10.2@658f1be311a230e0907f5dfe0213742aff0596de', 'ondram/ci-detector' => '3.5.1@594e61252843b68998bddd48078c5058fe9028bd', 'ondrejmirtes/better-reflection' => '4.3.45@3a559356763161db915cfa775b34cffe4f2c5905', 'phpdocumentor/reflection-common' => '2.2.0@1d01c49d4ed62f25aa84a747ad35d5a16924662b', 'phpdocumentor/reflection-docblock' => '4.3.4@da3fd972d6bafd628114f7e7e036f45944b62e9c', 'phpdocumentor/type-resolver' => '1.4.0@6a467b8989322d92aa1c8bf2bebcc6e5c2ba55c0', 'phpstan/php-8-stubs' => '0.1.9@c6d1a53f65c38e0f356cc4e380716eceff857e58', 'phpstan/phpdoc-parser' => '0.4.9@98a088b17966bdf6ee25c8a4b634df313d8aa531', 'phpstan/phpstan-phpunit' => '0.12.16@1dd916d181b0539dea5cd37e91546afb8b107e17', 'phpstan/phpstan-src' => '0.12.57@b5a2fe2120e58bba9b28b8b3cc8fd3b823691344', 'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8', 'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f', 'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b', 'react/cache' => 'v1.1.0@44a568925556b0bd8cacc7b49fb0f1cf0d706a0c', 'react/child-process' => 'v0.6.1@6895afa583d51dc10a4b9e93cd3bce17b3b77ac3', 'react/dns' => 'v1.4.0@665260757171e2ab17485b44e7ffffa7acb6ca1f', 'react/event-loop' => 'v1.1.1@6d24de090cd59cfc830263cfba965be77b563c13', 'react/http' => 'v1.1.0@754b0c18545d258922ffa907f3b18598280fdecd', 'react/promise' => 'v2.8.0@f3cff96a19736714524ca0dd1d4130de73dbbbc4', 'react/promise-stream' => 'v1.2.0@6384d8b76cf7dcc44b0bf3343fb2b2928412d1fe', 'react/promise-timer' => 'v1.6.0@daee9baf6ef30c43ea4c86399f828bb5f558f6e6', 'react/socket' => 'v1.6.0@e2b96b23a13ca9b41ab343268dbce3f8ef4d524a', 'react/stream' => 'v1.1.1@7c02b510ee3f582c810aeccd3a197b9c2f52ff1a', 'ringcentral/psr7' => '1.3.0@360faaec4b563958b673fb52bbe94e37f14bc686', 'roave/signature' => '1.1.0@c4e8a59946bad694ab5682a76e7884a9157a8a2c', 'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d', 'symfony/cache' => 'v5.1.8@d7bc33e9f9028f49f87057e7944c076d9593f046', 'symfony/cache-contracts' => 'v2.2.0@8034ca0b61d4dd967f3698aaa1da2507b631d0cb', 'symfony/config' => 'v5.1.8@11baeefa4c179d6908655a7b6be728f62367c193', 'symfony/console' => 'v4.4.16@20f73dd143a5815d475e0838ff867bce1eebd9d5', 'symfony/debug' => 'v4.4.16@c87adf3fc1cd0bf4758316a3a150d50a8f957ef4', 'symfony/dependency-injection' => 'v5.1.8@829ca6bceaf68036a123a13a979f3c89289eae78', 'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665', 'symfony/error-handler' => 'v4.4.16@363cca01cabf98e4f1c447b14d0a68617f003613', 'symfony/event-dispatcher' => 'v4.4.16@4204f13d2d0b7ad09454f221bb2195fccdf1fe98', 'symfony/event-dispatcher-contracts' => 'v1.1.9@84e23fdcd2517bf37aecbd16967e83f0caee25a7', 'symfony/filesystem' => 'v5.1.8@df08650ea7aee2d925380069c131a66124d79177', 'symfony/finder' => 'v4.4.16@26f63b8d4e92f2eecd90f6791a563ebb001abe31', 'symfony/http-client-contracts' => 'v2.3.1@41db680a15018f9c1d4b23516059633ce280ca33', 'symfony/http-foundation' => 'v5.1.8@a2860ec970404b0233ab1e59e0568d3277d32b6f', 'symfony/http-kernel' => 'v4.4.16@109b2a46e470a487ec8b0ffea4b0bb993aaf42ed', 'symfony/polyfill-ctype' => 'v1.20.0@f4ba089a5b6366e453971d3aad5fe8e897b37f41', 'symfony/polyfill-mbstring' => 'v1.20.0@39d483bdf39be819deabf04ec872eb0b2410b531', 'symfony/polyfill-php73' => 'v1.20.0@8ff431c517be11c78c48a39a66d37431e26a6bed', 'symfony/polyfill-php80' => 'v1.20.0@e70aa8b064c5b72d3df2abd5ab1e90464ad009de', 'symfony/process' => 'v5.1.8@f00872c3f6804150d6a0f73b4151daab96248101', 'symfony/service-contracts' => 'v1.1.8@ffc7f5692092df31515df2a5ecf3b7302b3ddacf', 'symfony/var-dumper' => 'v5.1.8@4e13f3fcefb1fcaaa5efb5403581406f4e840b9a', 'symfony/var-exporter' => 'v5.1.8@b4048bfc6248413592462c029381bdb2f7b6525f', 'symfony/yaml' => 'v5.1.8@f284e032c3cefefb9943792132251b79a6127ca6', 'symplify/autowire-array-parameter' => 'dev-master@6b042921db122d88e0653f031ee75f4bdfd747f5', 'symplify/composer-json-manipulator' => 'dev-master@c508eb786847182d8c61ab78e2771e349fb2fa49', 'symplify/console-color-diff' => 'dev-master@9c6fc82326bec1f66ec51ade944d11a82fe9bd65', 'symplify/easy-testing' => 'dev-master@c223941033bd9837389f4023ee9940e9af8a5908', 'symplify/markdown-diff' => 'dev-master@1ae7e14a9f07eac3d8fe5a1bcdab509de12cf52c', 'symplify/package-builder' => 'dev-master@ac3f4c7f09f740de4f9ddae802a6efe765520fbb', 'symplify/php-config-printer' => 'dev-master@babeccc0296f01fa7b1687d8078cb4bc78216e7b', 'symplify/rule-doc-generator' => 'dev-master@816fcba95324146e0087f7e3d3a9449a4567c2de', 'symplify/set-config-resolver' => 'dev-master@e3382b1a8cf1961a61869aa636e947ccd58dd610', 'symplify/skipper' => 'dev-master@45e0b215e9b25201b8ef2ae441eee58f327f18a7', 'symplify/smart-file-system' => 'dev-master@9e158ca902e75bc7b74b099f35175d535bb5fc06', 'symplify/symplify-kernel' => 'dev-master@9934bcd07574ee0ebc68b0b7b240323999c29831', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'rector/rector-prefixed' => 'dev-master@4148d8afed15432957ac57ccd8c277e6888e4fae', 'rector/simple-php-doc-parser' => 'dev-master@4148d8afed15432957ac57ccd8c277e6888e4fae', 'rector/symfony-php-config' => 'dev-master@4148d8afed15432957ac57ccd8c277e6888e4fae', 'rector/rector' => 'dev-master@4148d8afed15432957ac57ccd8c277e6888e4fae');
    private function __construct()
    {
    }
    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!\class_exists(\_PhpScoper006a73f0e455\Composer\InstalledVersions::class, \false) || !\_PhpScoper006a73f0e455\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \_PhpScoper006a73f0e455\Composer\InstalledVersions::getRootPackage()['name'];
    }
    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName) : string
    {
        if (\class_exists(\_PhpScoper006a73f0e455\Composer\InstalledVersions::class, \false) && \_PhpScoper006a73f0e455\Composer\InstalledVersions::getRawData()) {
            return \_PhpScoper006a73f0e455\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \_PhpScoper006a73f0e455\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
