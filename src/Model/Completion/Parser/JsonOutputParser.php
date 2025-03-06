<?php

declare(strict_types=1);

namespace Devscast\Lugha\Model\Completion\Parser;

/**
 * Class JsonOutputParser.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class JsonOutputParser implements OutputParserInterface
{
    /**
     * @todo for structured output, find a way to parse the output into a model object
     */
    #[\Override]
    public function __invoke(string $output): mixed
    {
        return \json_decode($output, true);
    }
}
