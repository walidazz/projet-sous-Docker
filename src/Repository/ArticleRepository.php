<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
 public function __construct(ManagerRegistry $registry)
 {
  parent::__construct($registry, Article::class);
 }

 /**
  * @return Query
  */
 public function findAllQuery(): Query
 {

  return $this->createQueryBuilder('a')

   ->orderBy('a.createdAt', 'DESC')
   ->setMaxResults(10)
   ->getQuery();
 }

 public function findLike($key): Query
 {
  return $this->getEntityManager()
   ->createQuery("
                SELECT a FROM App\Entity\Article a
                WHERE a.title LIKE :key ")
   ->setParameter('key', '%' . $key . '%');

 }

 public function findThreeLast()
 {
  return $this->createQueryBuilder('a')
   ->andWhere('a.exampleField = :val')
   ->orderBy('a.id', 'ASC')
   ->setMaxResults(3)
   ->getQuery()
   ->getResult()
  ;
 }

/*
public function findBestUsers($limit = 2)
{
return $this->createQueryBuilder('u')
->join('u.ads', 'a')
->join('a.comments', 'c')
->select('u as user, AVG(c.rating) as avgRatings, COUNT(c) as sumComments')
->groupBy('u')
->having('sumComments > 3')
->orderBy('avgRatings', 'DESC')
->setMaxResults($limit)
->getQuery()
->getResult();
}

/*
public function findOneBySomeField($value): ?Article
{
return $this->createQueryBuilder('a')
->andWhere('a.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}
