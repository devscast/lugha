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

namespace Devscast\Lugha\Provider;

/**
 * Class Provider.
 *
 * @author bernard-ng <bernard@devscast.tech>
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
}
