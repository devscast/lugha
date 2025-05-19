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

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine\Types;

use Devscast\Lugha\Model\Embeddings\Vector;
use Devscast\Lugha\Retrieval\VectorStore\Doctrine\DatabasePlatform;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Class VectorType.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class VectorType extends Type
{
    public const string NAME = 'lugha_vector';

    #[\Override]
    public function getName(): string
    {
        return self::NAME;
    }

    #[\Override]
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return ['vector'];
    }

    #[\Override]
    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    /**
     * @throws Exception if the platform is not supported
     * @throws Exception if the column does not have a length
     * @throws Exception if the column length is not a positive integer
     */
    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $databasePlatform = DatabasePlatform::tryFrom($platform::class);

        if ($databasePlatform === null) {
            throw new Exception(\sprintf(
                'Vectors are not supported on platform "%s".',
                \get_debug_type($platform),
            ));
        }

        if (! isset($column['length'])) {
            // @phpstan-ignore-next-line
            throw new Exception(\sprintf('Column "%s" should have a length', $column['name']));
        }

        if (! is_int($column['length']) || $column['length'] <= 0) {
            throw new Exception(\sprintf('Column "%s" should have a positive integer length', $column['name']));
        }

        return match ($databasePlatform) {
            DatabasePlatform::MariaDB => \sprintf('VECTOR(%d)', $column['length']),
            DatabasePlatform::PostgresSQL => \sprintf('vector(%d)', $column['length']),
        };
    }

    /**
     * @throws ConversionException if the value cannot be converted
     */
    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Vector
    {
        if ($value === null) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        try {
            return match (true) {
                \is_array($value) => Vector::from($value),
                \is_string($value) => Vector::fromString($value),
                $value instanceof Vector => $value,
            };
        } catch (\Throwable $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    /**
     * @throws ConversionException if the value cannot be converted
     */
    #[\Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if ($value === null) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        try {
            $value = match (true) {
                \is_array($value) => Vector::from($value),
                \is_string($value) => Vector::fromString($value),
                $value instanceof Vector => $value,
            };

            return match (DatabasePlatform::from($platform::class)) {
                DatabasePlatform::MariaDB => \sprintf("Vec_FromText('%s')", $value->toString()),
                default => $value->toString()
            };
        } catch (\Throwable $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }
}
