<?php

namespace App\Repository;

use App\Entity\TopicCommentReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
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


    /**
     * @param $id
     * @return array
     * @throws DBALException
     */
    public function findAllReported($id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM topic_comment_report 
                INNER JOIN p5_topicscomments 
                    ON p5_topicscomments.id = topic_comment_report.comment_id 
                INNER JOIN p5_users 
                    ON p5_users.id = p5_topicscomments.author_id 
            WHERE topic_comment_report.comment_id = $id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute($id);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
     * @return mixed
     */
    public function findAllReport(){
        return $this->createQueryBuilder('r')
            ->innerJoin('r.comment', 'c')
            ->select('c.topicCommentReports')
            ->where('c.id')
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
            ->select('COUNT(a)')
            ->orderBy('a.report', 'ASC');
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    public function FindByPage($nb_topics_page,$offset){

        $q = $this->createQueryBuilder('a')
            ->select('a')
            ->setFirstResult($offset)
            ->setMaxResults($nb_topics_page)
            ->orderBy('a.report','DESC')
        ;

        return $q->getQuery()->getResult();
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
