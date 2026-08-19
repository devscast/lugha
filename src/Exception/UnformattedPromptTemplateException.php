<?php

/*
 *  This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@ngandu.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ngandu\Lugha\Exception;

/**
 * Class UnformattedPromptTemplateException.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class UnformattedPromptTemplateException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('The prompt template has not been formatted yet');
    }
}
