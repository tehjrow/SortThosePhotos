<?php

namespace App\Repository\ShootProof;

use App\Entity\ShootProof\SpEventDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpEventDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpEventDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpEventDetails[]    findAll()
 * @method SpEventDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpEventDetailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpEventDetails::class);
    }

    // /**
    //  * @return SpEventDetails[] Returns an array of SpEventDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpEventDetails
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
