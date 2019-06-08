<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Users::class);
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
    public function FindByPage($nb_users_page,$offset){

        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->setFirstResult($offset)
            ->setMaxResults($nb_users_page)
            ->orderBy('a.createdAt','desc')
        ;

        return $q->getQuery()->getResult();
    }

    /**
     * @param $value
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
//    public function FindByIf($value)
//    {
//        return $this->createQueryBuilder('u')
//            ->innerJoin('u.articles', 'c')
//            ->addSelect('c')
//            ->andWhere('c.author = :id')
//            ->setParameter('id', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getSingleScalarResult()
//        ;
//    }


    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
