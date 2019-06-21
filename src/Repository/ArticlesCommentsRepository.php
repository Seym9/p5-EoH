<?php

namespace App\Repository;

use App\Entity\ArticlesComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArticlesComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticlesComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticlesComments[]    findAll()
 * @method ArticlesComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesCommentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArticlesComments::class);
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

    // /**
    //  * @return ArticlesComments[] Returns an array of ArticlesComments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticlesComments
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
