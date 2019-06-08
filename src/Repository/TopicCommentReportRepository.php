<?php

namespace App\Repository;

use App\Entity\TopicCommentReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TopicCommentReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicCommentReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicCommentReport[]    findAll()
 * @method TopicCommentReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicCommentReportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TopicCommentReport::class);
    }

    // /**
    //  * @return TopicCommentReport[] Returns an array of TopicCommentReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TopicCommentReport
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
