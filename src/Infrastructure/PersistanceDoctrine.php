<?php

namespace xVer\Component\PersistanceDoctrineComponent\Infrastructure;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use xVer\Bundle\DomainBundle\Domain\EntityInterface;
use xVer\Bundle\DomainBundle\Infrastructure\PersistanceInterface;

/**
 * @template T of object
 * @template-extends ServiceEntityRepository<T>
 */
abstract class PersistanceDoctrine extends ServiceEntityRepository implements PersistanceInterface
{
    /**
     * @param string $entityClass The class name of the entity this repository manages
     * @psalm-param class-string<T> $entityClass
     */
    public function __construct(private ManagerRegistry $managerRegistry, string $entityClass)
    {
        parent::__construct($this->managerRegistry, $entityClass);
    }

    /** @return PersistanceDoctrine<T> */
    public function emPersist(EntityInterface $object): self
    {
        $this->_em->persist($object);
        return $this;
    }

    /** @return PersistanceDoctrine<T> */
    public function emFlush(): self
    {
        $this->_em->flush();
        return $this;
    }

    /** @return PersistanceDoctrine<T> */
    public function emRemove(EntityInterface $object): self
    {
        $this->_em->remove($object);
        return $this;
    }

    public function beginTransaction(): void
    {
        $this->_em->beginTransaction();
    }

    public function commit(): void
    {
        $this->_em->commit();
    }

    public function rollback(): void
    {
        $this->_em->rollback();
    }
}
