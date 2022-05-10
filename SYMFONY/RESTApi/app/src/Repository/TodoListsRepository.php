<?php

namespace App\Repository;

use App\Entity\TodoLists;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TodoLists|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoLists|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoLists[]    findAll()
 * @method TodoLists[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoListsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoLists::class);
    }

    // /**
    //  * @return TodoLists[] Returns an array of TodoLists objects
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
    public function findOneBySomeField($value): ?TodoLists
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
