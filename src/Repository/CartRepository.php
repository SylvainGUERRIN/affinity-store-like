<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * @param $product
     * @param $user
     * @return Cart Returns an Cart object or null
     * @throws NonUniqueResultException
     */

    public function findByUserAndProduct($product, $user): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.product_name = :product')
            ->setParameter('product', $product)
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            //->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param $product
     * @param $user
     * @return array Returns Cart array
     */

    public function findByUserAndAncientProduct($product, $user): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.product_name != :product')
            ->setParameter('product', $product)
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $user
     * @return array Returns Cart array
     */

    public function findByUserID($user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
