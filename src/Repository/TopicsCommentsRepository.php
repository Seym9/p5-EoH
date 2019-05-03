<?php

namespace App\Repository;

use App\Entity\TopicsComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TopicsComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicsComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicsComments[]    findAll()
 * @method TopicsComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicsCommentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TopicsComments::class);
    }

    // /**
    //  * @return TopicsComments[] Returns an array of TopicsComments objects
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
    public function findOneBySomeField($value): ?TopicsComments
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
