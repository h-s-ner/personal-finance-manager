<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Transaction;
use App\Enum\TransactionType;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    //    /**
    //     * @return Transaction[] Returns an array of Transaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transaction
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
        * @return Transaction[] Returns an array of Transaction objects
    */
    public function search(String $value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.description LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findFiltered(
    ?TransactionType $type,
    ?Category $category,
    ?DateTime $fromDate,
    ?DateTime $tillDate
    ): array
    {
        $query = $this->createQueryBuilder('t');

        if ($type !== null) {
            $query
                ->andWhere('t.type = :type')
                ->setParameter('type', $type->value);
        }

        if ($category !== null) {
            $query
                ->andWhere('t.category = :category')
                ->setParameter('category', $category);
        }

        if ($fromDate !== null) {
            $query
                ->andWhere('t.date >= :fromDate')
                ->setParameter('fromDate', $fromDate);
        }

        if ($tillDate !== null) {
            $query
                ->andWhere('t.date <= :tillDate')
                ->setParameter('tillDate', $tillDate);
        }

        return $query
            ->getQuery()
            ->getResult();
    }
    public function getTotalAmount(TransactionType $type)
    {
        return $this->createQueryBuilder('t')
            ->select('SUM(t.amount)')
            ->where('t.type=:type')
            ->setParameter(':type',$type)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function getRecentTransactions(int $limit = 5): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.date', 'DESC')
            ->addOrderBy('t.id','DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function getCurrentMonthTotalsByType(): array
    {
        $fromDate = new DateTime('first day of this month 00:00:00');
        $tillDate = new DateTime('first day of next month 00:00:00');

        $results =  $this->createQueryBuilder('t')
            ->select('t.type, SUM(t.amount) as total')
            ->andWhere('t.date >= :fromDate')
            ->andWhere('t.date < :tillDate')
            ->setParameter('fromDate', $fromDate)
            ->setParameter('tillDate', $tillDate)
            ->groupBy('t.type')
            ->getQuery()
            ->getResult();
        $totals = [];
         foreach ($results as $result) {
            $totals[$result['type']->value] = (float) $result['total'];
        }
        return $totals;
    }
    public function getMinYear(): int
    {
       return (int) $this->createQueryBuilder('t')
       ->select('min(t.date)')
       ->getQuery()
       ->getSingleScalarResult();
    }    
}
