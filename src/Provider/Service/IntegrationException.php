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

namespace Devscast\Lugha\Provider\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * Class IntegrationException.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class IntegrationException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        if ($previous instanceof ClientExceptionInterface || $previous instanceof ServerExceptionInterface) {
            $response = $previous->getResponse()->getContent(throw: false);
            $message .= " Response: {$response}";
        }

        parent::__construct($message, $code, $previous);
    }
}
