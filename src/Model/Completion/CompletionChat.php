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

namespace Devscast\Lugha\Model\Completion;

use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Chat\Message;
use Devscast\Lugha\Model\Completion\Chat\Role;
use Devscast\Lugha\Provider\Service\HasCompletionSupport;

/**
 * Class Chatter.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class CompletionChat implements ChatInterface
{
    public function __construct(
        private HasCompletionSupport $client,
        private CompletionConfig $completionConfig,
    ) {
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function completion(string $input, ?array $tools = null): string
    {
        return $this->client->completion($input, $this->completionConfig, $tools)->completion;
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function completionWithHistory(string $input, History $history, ?array $tools = null): string
    {
        $history->append(new Message($input, Role::USER));

        return $this->client->completion($history, $this->completionConfig, $tools)->completion;
    }
}
