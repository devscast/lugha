<?php

/*
 *  This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@devscast.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Service;

use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\CompletionConfig;
use Devscast\Lugha\Provider\Response\CompletionResponse;

/**
 * Interface HasCompletionSupport.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface HasCompletionSupport
{
    /**
     * generates a completion for a prompt or chat history conversation.
     *
     * @param History|string $input The prompt to use for completion.
     * @param CompletionConfig $config The configuration to use for completion.
     * @param array|null $tools The tools to use for completion (optional).
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     * @throws \InvalidArgumentException when the prompt is empty.
     */
    public function completion(History|string $input, CompletionConfig $config, ?array $tools = null): CompletionResponse;
}
