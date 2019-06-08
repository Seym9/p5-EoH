<?php

namespace App\Repository;

use App\Entity\TopicReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TopicReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicReport[]    findAll()
 * @method TopicReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicReportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TopicReport::class);
    }

    // /**
    //  * @return TopicReport[] Returns an array of TopicReport objects
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
    public function findOneBySomeField($value): ?TopicReport
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
