<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])

    ->withSkip([
        PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer::class
    ])

    ->withPreparedSets(
        psr12: true,
        common: true,
        cleanCode: true
    );
