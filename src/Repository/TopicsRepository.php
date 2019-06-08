<?php

namespace App\Repository;

use App\Entity\Topics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Topics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topics[]    findAll()
 * @method Topics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Topics::class);
    }

    // /**
    //  * @return Topics[] Returns an array of Topics objects
    //  */

    public function findByThree()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

        public function report()
    {
        return $this->createQueryBuilder('t')
            ->where('t.report')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByCategory()
    {
        return $this->createQueryBuilder('t')
            ->where('t.categoryId')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
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
//, $category = null
//->where('a.categoryId')
    public function FindByPage($nb_topics_page,$offset){

        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->setFirstResult($offset)
            ->setMaxResults($nb_topics_page)
            ->orderBy('a.createdAt','desc')
        ;

        return $q->getQuery()->getResult();
    }

//    public function FindByPage($nb_topics_page,$offset, $category){
//
//        $q = $this->createQueryBuilder('a')
//            ->select('a')
//            ->where('a.categoryId')
//            ->setFirstResult($offset)
//            ->setMaxResults($nb_topics_page)
//            ->orderBy('a.createdAt','desc')
//        ;
//
//        return $q->getQuery()->getResult();
//    }

//    public function findByNbOfComments()
//    {
//        return $this->createQueryBuilder('t')
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(5)
//            ->getQuery()
//            ->getResult()
//            ;
//    }


    /*
    public function findOneBySomeField($value): ?Topics
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
