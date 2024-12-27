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

namespace Devscast\Lugha\Model\Completion\Tools;

use Devscast\Lugha\Assert;

/**
 * Class FunctionInfo.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class FunctionInfo
{
    /**
     * @param array<Parameter> $parameters
     */
    public function __construct(
        public string $name,
        public string $description,
        public array $parameters
    ) {
        Assert::notEmpty($name);
        Assert::notEmpty($description);
        Assert::allIsInstanceOf($parameters, Parameter::class);
    }

    public function getRequiredParameters(): array
    {
        return array_map(
            fn (Parameter $parameter) => $parameter->name,
            array_filter($this->parameters, fn (Parameter $parameter) => $parameter->required)
        );
    }
}
