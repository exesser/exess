<?php declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __DIR__ . '/src/Bundle',
    ]);

    $parameters->set(Option::DATA_TO_REMOVE, [
        ComposerJsonSection::REQUIRE => [
            'exesser/doctrine-extensions-bundle' => '*',
        ],
    ]);

    $parameters->set(Option::DATA_TO_APPEND, [
        ComposerJsonSection::REQUIRE_DEV => [
            'slevomat/coding-standard' => '^7.0',
            'squizlabs/php_codesniffer' => '^3.6',
        ],
    ]);
};
