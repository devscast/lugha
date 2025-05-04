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

use Devscast\Lugha\Exception\RuntimeException;
use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Embeddings\Distance;
use Devscast\Lugha\Model\Embeddings\EmbeddingsGeneratorInterface;
use Devscast\Lugha\Model\Embeddings\Vector;
use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\VectorStore\VectorStoreInterface;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineVectorStore.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
readonly class DoctrineVectorStore implements VectorStoreInterface
{
    /**
     * @template T of DocumentEntity
     *
     * @param class-string<T> $entityClassName
     *
     ** @throws Exception if the platform is not supported
     * @throws \RuntimeException if the "doctrine/orm" package is not installed
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private string $entityClassName,
        private EmbeddingsGeneratorInterface $embeddingsGenerator
    ) {
        if (! interface_exists(EntityManagerInterface::class)) {
            throw new \RuntimeException('The "doctrine/orm" package is required to use this feature.');
        }

        $platform = $this->entityManager->getConnection()->getDatabasePlatform();

        if (! in_array(VectorType::NAME, Type::getTypesMap(), true)) {
            Type::addType(VectorType::NAME, VectorType::class);
            $platform->registerDoctrineTypeMapping(VectorType::NAME, VectorType::NAME);
        }

        foreach (DatabasePlatform::from($platform::class)->distanceFunctions() as $distance => $class) {
            $this->entityManager->getConfiguration()->addCustomStringFunction($distance, $class);
        }
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function addDocument(Document $document): void
    {
        $this->persist($document);
        $this->entityManager->flush();
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function addDocuments(iterable $documents): void
    {
        foreach ($documents as $document) {
            $this->persist($document);
        }

        $this->entityManager->flush();
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function similaritySearch(string $query, int $k = 4, Distance $distance = Distance::COSINE): array
    {
        $queryEmbeddings = $this->embeddingsGenerator->embedQuery($query);

        return $this->similaritySearchByVector($queryEmbeddings, $k, $distance);
    }

    #[\Override]
    public function similaritySearchByVector(Vector $vector, int $k = 4, Distance $distance = Distance::COSINE): array
    {
        $repository = $this->entityManager->getRepository($this->entityClassName);

        /** @var DocumentEntity[] $results */
        $results = $repository->createQueryBuilder('d')
            ->orderBy(sprintf('%s(d.embeddings, :vector)', $distance->value), 'ASC')
            ->setParameter('vector', $vector->toString())
            ->setMaxResults($k)
            ->getQuery()
            ->getResult();

        return $results;
    }

    /**
     * @throws ServiceIntegrationException if the embedding service fails
     * @throws RuntimeException if the document is not an instance of DocumentEntity
     */
    private function persist(Document $document): void
    {
        if (! $document instanceof DocumentEntity) {
            throw new RuntimeException(\sprintf(
                'The document must be an instance of %s.',
                DocumentEntity::class
            ));
        }

        if ($document->hasEmbeddings() === false) {
            $document = $this->embeddingsGenerator->embedDocument($document);
        }

        $this->entityManager->persist($document);
    }
}
