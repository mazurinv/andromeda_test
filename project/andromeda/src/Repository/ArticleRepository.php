<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
     * @param array $tags
    * @return Article[] Returns an array of Article objects
    */
    public function getArticlesByTags($tags)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        foreach ($tags as $key => $tag) {
            $queryBuilder = $queryBuilder
                ->innerJoin('a.tags', 't' . $key)
                ->andWhere('t' . $key . '.id = :t' . $key)
                ->setParameter('t' . $key, $tag);
        }
        return $queryBuilder
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
