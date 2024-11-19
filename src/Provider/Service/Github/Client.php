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

namespace Devscast\Lugha\Provider\Service\Github;

use Devscast\Lugha\Provider\Service\AbstractClient;

/**
 * Class Client.
 *
 * @see https://github.com/marketplace/models/
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class Client extends AbstractClient
{
    protected const string BASE_URI = 'https://models.inference.ai.azure.com/';
}
