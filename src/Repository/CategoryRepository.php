<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
 public function __construct(ManagerRegistry $registry)
 {
  parent::__construct($registry, Category::class);
 }

 /**
  * @return Query
  */
 public function findAllByCategory($value): Query
 {
  return $this->createQueryBuilder('a')

   ->orderBy('a.createdAt', 'DESC')
   ->join('a.category', 'c')
  // ->select('a as article, c.libelle')
   ->andWhere('c.libelle = :val')
   ->setParameter('val', $value)
   ->orderBy('a.createdAt', 'DESC')
   ->setMaxResults(10)
   ->getQuery();
 }

 // /**
 //  * @return Category[] Returns an array of Category objects
 //  */
 /*
 public function findByExampleField($value)
 {
 return $this->createQueryBuilder('c')
 ->andWhere('c.exampleField = :val')
 ->setParameter('val', $value)
 ->orderBy('c.id', 'ASC')
 ->setMaxResults(10)
 ->getQuery()
 ->getResult()
 ;
 }
  */

 /*
public function findOneBySomeField($value): ?Category
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
