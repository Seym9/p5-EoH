<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

use InvalidArgumentException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    /**
     * @return Query
     */
    public function findAllVisible(): Query
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
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

    public function FindByPage($nb_articles_page,$offset){

        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->setFirstResult($offset)
            ->setMaxResults($nb_articles_page)
            ->orderBy('a.createdAt','desc')
        ;

        return $q->getQuery()->getResult();
    }


    /**
     * @param $value
     * @param $nb
     * @return mixed
     */
    public function FindAllArticles($value, $nb)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.author', 'u')
            ->addSelect('u')
            ->andWhere('a.author = :id')
            ->setParameter('id', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
        ;
    }
//     /**
//     * @return Articles[] Returns an array of Articles objects
//     */
//
//    public function pagination($value)
//    {
//        return $this->createQueryBuilder('a')
//
//
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(5)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    // /**
    //  * @return Articles[] Returns an array of Articles objects
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
    public function findOneBySomeField($value): ?Articles
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
