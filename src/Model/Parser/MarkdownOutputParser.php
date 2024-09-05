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

namespace Devscast\Lugha\Model\Parser;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Class MarkdownOutputParser.
 *
 * @see https://commonmark.thephpleague.com/
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class MarkdownOutputParser implements OutputParserInterface
{
    private MarkdownConverter $converter;

    public function __construct(array $config = [])
    {
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'disallowed_raw_html' => [
                'disallowed_tags' => [
                    'title',
                    'textarea',
                    'style',
                    'xmp',
                    'iframe',
                    'noembed',
                    'noframes',
                    'script',
                    'plaintext',
                ],
            ],
            ...$config,
        ]);
        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new AutolinkExtension())
            ->addExtension(new DisallowedRawHtmlExtension())
            ->addExtension(new TableExtension());
        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * @throws CommonMarkException
     */
    #[\Override]
    public function __invoke(string $output): string
    {
        return trim($this->converter->convert($output)->getContent());
    }
}
