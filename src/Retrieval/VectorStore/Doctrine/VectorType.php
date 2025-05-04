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

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine;

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Model\Embeddings\Vector;
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
    public const string NAME = 'vector';

    #[\Override]
    public function getName(): string
    {
        return self::NAME;
    }

    #[\Override]
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
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
            throw new Exception(\sprintf(
                'Column "%s" should have a length',
                \get_debug_type($column['name']),
            ));
        }

        if (! is_int($column['length']) || $column['length'] <= 0) {
            throw new Exception(\sprintf(
                'Column "%s" should have a positive integer length',
                \get_debug_type($column['name'])
            ));
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
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Vector
    {
        if ($value === null) {
            return null;
        }

        try {
            if (\is_array($value)) {
                return Vector::from($value);
            }

            if (! \is_string($value)) {
                throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string', 'array']);
            }
            return Vector::fromString($value);

        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    /**
     * @throws ConversionException if the value cannot be converted
     */
    #[\Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        $databasePlatform = DatabasePlatform::from($platform::class);

        if ($value === null) {
            return null;
        }

        if ($value instanceof Vector) {
            return match ($databasePlatform) {
                DatabasePlatform::MariaDB => \sprintf('Vec_FromText(%s)', $value->toString()),
                default => $value->toString()
            };
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', Vector::class]);
    }
}
