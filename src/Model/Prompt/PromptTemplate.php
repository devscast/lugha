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

namespace Devscast\Lugha\Model\Prompt;

use Webmozart\Assert\Assert;

/**
 * Class PromptTemplate.
 * Lets you create a prompt template that can be formatted with values.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PromptTemplate implements \Stringable
{
    private readonly string $template;

    private ?string $prompt = null;

    public function __construct(string $template, array $values = [])
    {
        Assert::notEmpty($template, 'Template cannot be empty');

        if (! empty($values)) {
            $this->format($values);
        }

        $this->template = $template;
    }

    #[\Override]
    public function __toString(): string
    {
        if ($this->prompt === null) {
            throw new \RuntimeException('The template has not been formatted yet');
        }

        return $this->prompt;
    }

    /**
     * Create a new prompt from the given template.
     *
     * <code>
     *     $template = PromptTemplate::from("Hello {context}");
     * </code>
     */
    public static function from(string $template): self
    {
        return new self($template, []);
    }

    /**
     * Format the prompt with the given values.
     * The values should be an associative array where the key is the placeholder
     *
     * <code>
     *     $template->format(["{context}" => "some context..."]);
     * </code>
     *
     * @param array<string, string> $values
     */
    public function format(array $values): self
    {
        Assert::notEmpty($values, 'Values cannot be empty');

        $this->prompt = str_replace(
            search: array_keys($values),
            replace: array_values($values),
            subject: $this->template,
        );

        return $this;
    }
}
