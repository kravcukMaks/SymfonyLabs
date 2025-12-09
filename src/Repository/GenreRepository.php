<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Genre>
 *
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

//    /**
//     * @return Genre[] Returns an array of Genre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

public function search(?string $search): \Doctrine\ORM\QueryBuilder
{
    $qb = $this->createQueryBuilder('g');

    if ($search) {
        $qb->andWhere('LOWER(g.name) LIKE :s')
           ->setParameter('s', '%' . strtolower($search) . '%');
    }

    return $qb;
}
}
