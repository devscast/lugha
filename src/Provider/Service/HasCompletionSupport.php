<?php

/*
 *  This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@ngandu.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ngandu\Lugha\Provider\Service;

use Ngandu\Lugha\Exception\ServiceIntegrationException;
use Ngandu\Lugha\Model\Completion\Chat\History;
use Ngandu\Lugha\Model\Completion\CompletionConfig;
use Ngandu\Lugha\Provider\Response\CompletionResponse;

/**
 * Interface HasCompletionSupport.
 *
 * @author bernard-ng <bernard@ngandu.dev>
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
