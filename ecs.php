<?php

/*
 * This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@devscast.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ])

    ->withSkip([
        PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer::class
    ])

    ->withPreparedSets(
        psr12: true,
        common: true,
        cleanCode: true
    );
