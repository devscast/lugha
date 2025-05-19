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

namespace Devscast\Lugha\Retrieval\Loader\Reader;

use Devscast\Lugha\Exception\IOException;
use Smalot\PdfParser\{Config, Parser};
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PdfReader.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class PdfReader extends AbstractReader
{
    public const string SUPPORTED_EXTENSIONS_PATTERN = '/pdf/';

    private Config $config;

    private Parser $parser;

    public function __construct(
        Filesystem $filesystem = new Filesystem()
    ) {
        parent::__construct($filesystem);
        if (class_exists(Parser::class) === false) {
            throw new \RuntimeException('The "smalot/pdfparser" package is required to read PDF files.');
        }

        $this->config = new Config();

        // It won't retain image content anymore, but will use less memory too.
        $this->config->setRetainImageContent(false);
        $this->parser = new Parser(config: $this->config);
    }

    #[\Override]
    public function readContent(string $path, bool $skipExtensionCheck = false): string
    {
        if ($skipExtensionCheck === false) {
            $this->ensureSupported($path);
            $this->ensureFileExists($path);
        }

        try {
            $pdf = $this->parser->parseFile($path);
        } catch (\Exception $e) {
            throw new IOException($path, previous: $e);
        }

        return $pdf->getText();
    }
}
