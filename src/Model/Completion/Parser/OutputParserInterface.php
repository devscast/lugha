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

namespace Devscast\Lugha\Model\Completion\Parser;

/**
 * Interface OutputParserInterface.
 *
 * This interface defines a method for parsing the output of a Large Language Model (LLM) and returning the desired output.
 * The output can be a string, an array, or an object, depending on the implementation.
 *
 * For example, you may want to parse the output of a model that returns a JSON string or Markdown text.
 * The implementing classes will define how to handle and process different types of model outputs.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface OutputParserInterface
{
    /**
     * Parses the output returned by a LLM model and returns the desired output in the expected format.
     *
     * The method will be invoked to process the model's raw output, which could be in various formats (e.g., JSON, Markdown, plain text),
     * and return it in a structured format (e.g., array, object, etc.).
     *
     * @param string $output The raw output returned by the LLM model.
     *
     * @return mixed The processed output, which can be a string, array, or object, depending on the specific implementation.
     */
    public function __invoke(string $output): mixed;
}
