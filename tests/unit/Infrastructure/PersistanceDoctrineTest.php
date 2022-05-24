<?php

namespace Tests\unit\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use xVer\Bundle\DomainBundle\Domain\EntityInterface;
use xVer\Component\PersistanceDoctrineComponent\Infrastructure\PersistanceDoctrine;


/**
 * @covers xVer\Component\PersistanceDoctrineComponent\Infrastructure\PersistanceDoctrine
 */
class PersistanceDoctrineTest extends TestCase
{
    public function testPersistanceDoctrine(): void
    {
        $classMetadata = $this->getMockBuilder(ClassMetadata::class)->disableOriginalConstructor()->getMock();
        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $managerRegistry->expects($this->once())->method('getManagerForClass')->willReturn($entityManager);
        $entityManager->expects($this->once())->method('getClassMetadata')->willReturn($classMetadata);

        $object1 = new class implements EntityInterface { public int $id = 1;
        public function sameId(EntityInterface $otherEntity): bool
        {
            return $this->id === $otherEntity->id;
        }};

        $stub = $this->getMockForAbstractClass(PersistanceDoctrine::class, [
            $managerRegistry,
            TestEntity::class
        ]);
        $this->assertSame($stub, $stub->emPersist($object1));
        $this->assertSame($stub, $stub->emFlush());
	    $this->assertSame($stub, $stub->emRemove($object1));
	    $stub->beginTransaction();
        $stub->commit();
        $stub->rollback();
    }
}
