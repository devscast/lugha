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

namespace Ngandu\Lugha\Provider\Service\Client;

use Ngandu\Lugha\Provider\Provider;
use Ngandu\Lugha\Provider\Service\Client;

/**
 * Class AnthropicClient.
 *
 * @see https://docs.anthropic.com/en/api/getting-started
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class AnthropicClient extends Client
{
    protected const string BASE_URI = 'https://api.anthropic.com/v1/';

    protected Provider $provider = Provider::ANTHROPIC;
}
