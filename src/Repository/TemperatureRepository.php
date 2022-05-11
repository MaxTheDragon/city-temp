<?php
/**
 * City Temperature System
 */
namespace App\Repository;

use App\Entity\Temperature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * The TemperatureRepository class which contains methods to retrieve and store temperature records in the database.
 *
 * @extends ServiceEntityRepository<Temperature>
 *
 * @method Temperature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Temperature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Temperature[]    findAll()
 * @method Temperature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemperatureRepository extends ServiceEntityRepository
{
    /**
     * Creates a new TemperatureRepository object.
     *
     * @param $registry The Doctrine manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Temperature::class);
    }

    /**
     * Adds a new Temperature entity to the database.
     *
     * @param Temperature $entity The Temperature entity object to add.
     * @param bool $flush Whether or not to immediately flush this addition to the database.
     */
    public function add(Temperature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Removes a Temperature entity from the database.
     *
     * @param Temperature $entity The Temperature entity object to remove.
     * @param bool $flush Whether or not to immediately flush this removal to the database.
     */
    public function remove(Temperature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
