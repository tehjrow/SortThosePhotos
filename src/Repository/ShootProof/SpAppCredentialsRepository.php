<?php

namespace App\Repository\ShootProof;

use App\Entity\ShootProof\SpAppCredentials;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpAppCredentials|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpAppCredentials|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpAppCredentials[]    findAll()
 * @method SpAppCredentials[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpAppCredentialsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpAppCredentials::class);
    }

    // /**
    //  * @return SpAppCredentials[] Returns an array of SpAppCredentials objects
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
    public function findOneBySomeField($value): ?SpAppCredentials
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
