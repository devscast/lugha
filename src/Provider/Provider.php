<?php

/*
 * This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@ngandu.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ngandu\Lugha\Provider;

/**
 * Class Provider.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
enum Provider: string
{
    case GOOGLE = 'GOOGLE';
    case MISTRAL = 'MISTRAL';
    case OPENAI = 'OPENAI';
    case OLLAMA = 'OLLAMA';
    case GITHUB = 'GITHUB';
    case ANTHROPIC = 'ANTHROPIC';
    case VOYAGER = 'VOYAGER';
    case DEEPSEEK = 'DEEPSEEK';

    public function getEnvName(): string
    {
        return "{$this->value}_API_KEY";
    }
}
