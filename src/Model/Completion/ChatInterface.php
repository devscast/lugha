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

use Devscast\Lugha\Model\Completion\Chat\History;

/**
 * Interface ChatInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface ChatInterface
{
    public function completion(string $input, ?array $tools = null): string;

    public function completionWithHistory(string $input, History $history, ?array $tools = null): string;
}
