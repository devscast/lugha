<?php

declare(strict_types=1);

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine\Types;

use Devscast\Lugha\Retrieval\Metadata;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Class MetadataType.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class MetadataType extends Type
{
    public const string NAME = 'lugha_metadata';

    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL([
            'nullable' => true,
        ]);
    }

    #[\Override]
    public function getName(): string
    {
        return self::NAME;
    }

    #[\Override]
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return ['json'];
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Metadata
    {
        if ($value === null) {
            return null;
        }

        if (! \is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string', Metadata::class]);
        }

        try {
            return Metadata::tryFrom($value);
        } catch (\Throwable $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    #[\Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Metadata) {
            return \json_encode($value) ?: null;
        }

        if ($value === null || $value === '') {
            return null;
        }

        if (! \is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string', Metadata::class]);
        }

        throw ConversionException::conversionFailed($value, $this->getName());
    }
}
