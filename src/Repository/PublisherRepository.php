<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Publisher>
 *
 * @method Publisher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publisher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publisher[]    findAll()
 * @method Publisher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublisherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

//    /**
//     * @return Publisher[] Returns an array of Publisher objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

public function search(?string $search): \Doctrine\ORM\QueryBuilder
{
    $qb = $this->createQueryBuilder('p');

    if ($search) {
        $qb->andWhere('LOWER(p.name) LIKE :s OR LOWER(p.address) LIKE :s')
           ->setParameter('s', '%' . strtolower($search) . '%');
    }

    return $qb;
}

}
