<?php
namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryDeletionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function delete(
        Category $category,
        ?Category $replacementCategory = null
    ): void {
        $this->entityManager->wrapInTransaction(function () use (
            $category,
            $replacementCategory
        ) {
            if ($replacementCategory === $category) {
                throw new \InvalidArgumentException(
                    'The replacement category must be different.'
                );
            }
            if ($replacementCategory !== null) {
                $this->entityManager->createQuery(
                    'UPDATE App\Entity\Transaction t
                     SET t.category = :replacement
                     WHERE t.category = :category'
                )
                    ->setParameter('replacement', $replacementCategory)
                    ->setParameter('category', $category)
                    ->execute();
            }

            $this->entityManager->remove($category);
            $this->entityManager->flush();
        });
    }
}
