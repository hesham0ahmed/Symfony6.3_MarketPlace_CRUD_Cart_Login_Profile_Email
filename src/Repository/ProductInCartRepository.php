<?php

namespace App\Repository;

use App\Entity\ProductInCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductInCart>
 *
 * @method ProductInCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductInCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductInCart[]    findAll()
 * @method ProductInCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductInCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductInCart::class);
    }

//    /**
//     * @return ProductInCart[] Returns an array of ProductInCart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductInCart
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
