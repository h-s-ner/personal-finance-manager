<?php

namespace App\Repository;

use App\Entity\Category;
use App\Enum\TransactionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findCategoriesForReport(
        ?DateTime $from = null,
        ?DateTime $to = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id, c.name, c.description, c.color')
            ->addSelect('COUNT(t.id) AS transactionCount')
            ->addSelect('SUM(CASE WHEN t.type = :income THEN t.amount ELSE 0 END) AS income')
            ->addSelect('SUM(CASE WHEN t.type = :expense THEN t.amount ELSE 0 END) AS expense')
            ->addSelect('
                SUM(
                    CASE
                        WHEN t.type = :income THEN t.amount
                        WHEN t.type = :expense THEN -t.amount
                        ELSE 0
                    END
                ) AS balance
            ')
            ->leftJoin('c.transactions', 't')
            ->setParameter('income', TransactionType::INCOME->value)
            ->setParameter('expense', TransactionType::EXPENSE->value)
            ->groupBy('c.id');
        if ($from !== null && $to !== null) {
            $qb->andWhere('t.date >= :from AND t.date < :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }
        return $qb->getQuery()->getResult();
    }
}
