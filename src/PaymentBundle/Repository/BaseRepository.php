<?php


namespace PaymentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class BaseRepository
 *
 */
abstract class BaseRepository extends EntityRepository
{
    /**
     * The doctrine - this will support for different object managers
     *
     * @var \Doctrine\Common\Persistence\ManagerRegistry $doctrine
     */
    protected $entityManager;

    /**
     * @InjectParams({
     *      "entityManager" = @Inject("doctrine.orm.entity_manager")
     * })
     *
     * @param null $entityManager
     *
     * @internal param ManagerRegistry $doctrine
     */
    public function setDoctrineManager($entityManager = null)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $entity
     * @return object
     */
    public function save( $entity )
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param $entity
     * @return void
     */
    public function delete( $entity )
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}