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
use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Exception\IOException;
use Devscast\Lugha\Exception\UnformattedPromptTemplateException;

/**
 * A class that allows the creation and management of prompt templates by replacing placeholders
 * with actual values. The templates can be created from a string or a file, and individual or multiple
 * parameters can be set to format the prompt.
 *
 * The class implements the \Stringable interface, meaning it can be converted into a string,
 * which will return the formatted prompt.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PromptTemplate implements \Stringable
{
    /**
     * @var string The template string used for creating the prompt.
     */
    private readonly string $template;

    /**
     * @var string|null The formatted prompt after setting parameters.
     */
    private ?string $prompt = null;

    /**
     * @param string $template The template string to be used for prompt creation.
     * @param array $values An associative array of values to replace placeholders in the template.
     *
     * @throws InvalidArgumentException If the template is empty.
     */
    public function __construct(string $template, array $values = [])
    {
        Assert::notEmpty($template, 'Template cannot be empty');

        $this->template = $template;
        if (! empty($values)) {
            $this->setParameters($values);
        }
    }

    /**
     * Converts the prompt template to a string.
     *
     * This method returns the formatted prompt string after replacing placeholders
     * with actual values. It throws an exception if the prompt has not been formatted.
     *
     * @return string The formatted prompt.
     *
     * @throws UnformattedPromptTemplateException If the prompt has not been formatted yet.
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
     * Creates a new PromptTemplate instance from a raw template string.
     *
     * @param string $template The template string to initialize the prompt.
     *
     * @return self The newly created PromptTemplate instance.
     */
    public static function from(string $template): self
    {
        return new self($template, []);
    }

    /**
     * Creates a new PromptTemplate instance from a template file.
     *
     * @param string $path The path to the file containing the template.
     *
     * @return self The newly created PromptTemplate instance.
     *
     * @throws IOException If the template file cannot be read.
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
     * Sets multiple parameters in the template by replacing placeholders with values.
     *
     * @param array $values An associative array where keys are placeholder names and values are the replacements.
     *
     * @return self The updated PromptTemplate instance with replaced values.
     *
     * @throws InvalidArgumentException If the values array is empty.
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
     * Sets a single parameter in the template by replacing a placeholder with a value.
     *
     * @param string $name The placeholder name to be replaced.
     * @param string $value The value to replace the placeholder with.
     *
     * @return self The updated PromptTemplate instance with the replaced value.
     */
    public function setParameter(string $name, string $value): self
    {
        $this->prompt = \str_replace($name, $value, $this->template);

        return $this;
    }
}
