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

namespace Devscast\Lugha\Model\Completion\Prompt;

use Devscast\Lugha\Assert;
use Devscast\Lugha\Exception\IOException;
use Devscast\Lugha\Exception\UnformattedPromptTemplateException;

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

        $this->template = $template;
        if (! empty($values)) {
            $this->setParameters($values);
        }
    }

    /**
     * @throws UnformattedPromptTemplateException If the prompt has not been formatted yet
     */
    #[\Override]
    public function __toString(): string
    {
        if ($this->prompt === null) {
            throw new UnformattedPromptTemplateException();
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
     * Create a new prompt from the given file.
     *
     * <code>
     *     $template = PromptTemplate::fromFile("/path/to/file.txt");
     * </code>
     *
     * @throws IOException If the file cannot be read
     */
    public static function fromFile(string $path): self
    {
        $content = \file_get_contents($path);
        if ($content === false) {
            throw new IOException($path);
        }

        return new self($content, []);
    }

    /**
     * Format the prompt with the given values.
     * The values should be an associative array where the key is the placeholder
     *
     * <code>
     *     $template->setParameters(["{context}" => "some context..."]);
     * </code>
     *
     * @param array<string, string> $values
     */
    public function setParameters(array $values): self
    {
        Assert::notEmpty($values, 'Values cannot be empty');

        $this->prompt = \str_replace(
            search: \array_keys($values),
            replace: \array_values($values),
            subject: $this->template,
        );

        return $this;
    }

    /**
     * Format the prompt with a single given value.
     * Useful when programmatically building the prompt
     *
     * <code>
     *     $template->setParameter(':username', 'bernard-ng')
     * </code>
     */
    public function setParameter(string $name, string $value): self
    {
        $this->prompt = \str_replace($name, $value, $this->template);

        return $this;
    }
}
