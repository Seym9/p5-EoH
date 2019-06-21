<?php

namespace App\Repository;

use App\Entity\TopicsComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
    /**
     * @return int
     * @throws NonUniqueResultException
     */
    public function FindAllAsInt(){
        $qb=$this->createQueryBuilder('a')
            ->select('COUNT(a)');
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    public function FindByPage($nb_topics_page,$offset){

        $q = $this->createQueryBuilder('a')
            ->setFirstResult($offset)
            ->setMaxResults($nb_topics_page)
            ->orderBy('a.report','DESC')
        ;

        return $q->getQuery()->getResult();
    }

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
