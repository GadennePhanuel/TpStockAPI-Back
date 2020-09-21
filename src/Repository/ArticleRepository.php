<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param $idStock
     * @return Article[] Returns an array of Article objects
     */
    public function findAllArticleNotInCurrentStock($idStock, $idUser)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * 
            FROM article
            WHERE user_id = :userId AND !EXISTS (
                SELECT article_id
                FROM belong
                WHERE stock_id = :stock_id AND article.id = belong.article_id
                )   
            ORDER BY article.price    
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'stock_id' => $idStock,
            'userId' => $idUser
        ]);

        return $stmt->fetchAll();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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
    public function findOneBySomeField($value): ?Article
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
