<?php

namespace App\Repository;

use App\Entity\LibraryBranch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LibraryBranch>
 *
 * @method LibraryBranch|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraryBranch|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraryBranch[]    findAll()
 * @method LibraryBranch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryBranchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryBranch::class);
    }

//    /**
//     * @return LibraryBranch[] Returns an array of LibraryBranch objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LibraryBranch
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
