<?php

namespace App\Repository\ShootProof;

use App\Entity\ShootProof\SpIntegrationCredentials;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpIntegrationCredentials|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpIntegrationCredentials|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpIntegrationCredentials[]    findAll()
 * @method SpIntegrationCredentials[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpIntegrationCredentialsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpIntegrationCredentials::class);
    }

    // /**
    //  * @return SpIntegrationCredentials[] Returns an array of SpIntegrationCredentials objects
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
    public function findOneBySomeField($value): ?SpIntegrationCredentials
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
