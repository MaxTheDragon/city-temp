<?php
/**
 * City Temperature System
 */
namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * The CityRepository class which contains methods to retrieve and store cities in the database.
 *
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    /**
     * Creates a new CityRepository object.
     *
     * @param $registry The Doctrine manager registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }
    
    /**
     * Fetches a city from the database using its name.
     *
     * @param string $name The name of the city to look for.
     * @return City The city with the given name or null if it doesn't exist. 
     */
    public function findByName(string $name): ?City
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    /**
     * Fetches all cities from the database.
     *
     * @return array An array containing all cities from the database.
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Creates a new City entity object and adds it to the database.
     *
     * @param string $name The name for the new City entity.
     * @return City The newly created City entity.
     */
    public function create(string $name): City
    {
        // Create a new City entity object.
        $city = new City($name);
        
        // Add it to the database.
        $this->add($city, false);
        
        // Return it.
        return $city;
    }

    /**
     * Adds a new City entity to the database.
     *
     * @param City $entity The City entity object to add.
     * @param bool $flush Whether or not to immediately flush this addition to the database.
     */
    public function add(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Removes a City entity from the database.
     *
     * @param City $entity The City entity object to remove.
     * @param bool $flush Whether or not to immediately flush this removal to the database.
     */
    public function remove(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
