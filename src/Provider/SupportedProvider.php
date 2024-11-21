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
 * Class SupportedProvider.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum SupportedProvider: string
{
    case GOOGLE = 'google';
    case MISTRAL = 'mistral';
    case OPENAI = 'openai';
    case OLLAMA = 'ollama';
    case GITHUB = 'github';
    case ANTHROPIC = 'anthropic';
    case VOYAGER = 'voyager';
}
