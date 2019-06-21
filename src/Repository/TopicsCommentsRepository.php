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

    // /**
    //  * @return TopicsComments[] Returns an array of TopicsComments objects
    //  */

//    /**
//     * @param $value
//     * @return mixed
//     */
//    public function findByExampleField()
//    {
//        return $this->createQueryBuilder('t')
//            ->innerJoin('t.topicCommentReports', 'r')
//            ->andWhere('r.id')
//            ->innerJoin('t.author', 'a')
//            ->andWhere()
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//
//SELECT * FROM topic_comment_report
//INNER JOIN p5_topicscomments
//ON p5_topicscomments.id = topic_comment_report.comment_id
//INNER JOIN p5_users
//ON p5_users.id = p5_topicscomments.author_id
//
//    public function findByNbOfReports() {
//        return $this->createQueryBuilder('t')
//            ->innerJoin('t.topicCommentReports', 'r')
//            ->select('COUNT(r)')
//            ->getQuery()
//            ->getResult()
//            ;
//    }

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
