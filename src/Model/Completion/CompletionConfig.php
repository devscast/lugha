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

use Webmozart\Assert\Assert;

/**
 * Class CompletionConfig.
 *
 * @see https://platform.openai.com/docs/api-reference/chat/object
 * @see https://ai.google.dev/gemini-api/docs/text-generation?lang=rest#configure
 * @see https://docs.mistral.ai/api/#tag/chat
 * @see https://docs.anthropic.com/en/api/messages
 * @see https://github.com/ollama/ollama/blob/main/docs/modelfile.md#valid-parameters-and-values
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class CompletionConfig
{
    /**
     * @param string $model The model to use for generating the text.
     * @param float|null $temperature The value used to control the randomness of the generated text.
     * @param int|null $maxTokens The maximum number of tokens to generate.
     * @param float|null $topP The cumulative probability of the top tokens to keep.
     * @param int|null $topK The number of top tokens to keep.
     * @param float|null $frequencyPenalty The value used to penalize new tokens based on their frequency in the training data.
     * @param float|null $presencePenalty The value used to penalize new tokens based on whether they are already present in the text.
     * @param array|null $stopSequences A list of sequences where the model should stop generating the text.
     * @param array $additionalParameters Additional parameters to pass to the API.
     */
    public function __construct(
        public string $model,
        public ?float $temperature = null,
        public ?int $maxTokens = null,
        public ?float $topP = null,
        public ?int $topK = null,
        public ?float $frequencyPenalty = null,
        public ?float $presencePenalty = null,
        public ?array $stopSequences = null,
        public array $additionalParameters = []
    ) {
        Assert::notEmpty($this->model);
        Assert::nullOrRange($this->temperature, 0, 2);
        Assert::nullOrPositiveInteger($this->maxTokens);
        Assert::nullOrRange($this->topP, 0, 1);
        Assert::nullOrPositiveInteger($this->topK);
        Assert::nullOrRange($this->frequencyPenalty, -2, 2);
        Assert::nullOrRange($this->presencePenalty, -2, 2);
    }
}
