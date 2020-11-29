<?php

namespace App\Repository;

use App\Entity\MotsClesArticles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MotsClesArticles|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotsClesArticles|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotsClesArticles[]    findAll()
 * @method MotsClesArticles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotsClesArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MotsClesArticles::class);
    }

    // /**
    //  * @return MotsClesArticles[] Returns an array of MotsClesArticles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MotsClesArticles
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
