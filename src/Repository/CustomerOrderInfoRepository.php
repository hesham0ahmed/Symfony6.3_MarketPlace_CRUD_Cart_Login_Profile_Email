<?php

namespace App\Repository;

use App\Entity\CustomerOrderInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerOrderInfo>
 *
 * @method CustomerOrderInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerOrderInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerOrderInfo[]    findAll()
 * @method CustomerOrderInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerOrderInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerOrderInfo::class);
    }

//    /**
//     * @return CustomerOrderInfo[] Returns an array of CustomerOrderInfo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CustomerOrderInfo
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
