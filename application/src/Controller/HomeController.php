<?php

namespace App\Controller;

use App\Enum\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TransactionRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TransactionRepository $transactionRepository): Response
    {
        $income = $transactionRepository->getTotalAmount(TransactionType::INCOME);
        $expenses = $transactionRepository->getTotalAmount(TransactionType::EXPENSE);
        $totals = $transactionRepository->getCurrentMonthTotalsByType();
        
        $incomeThisMonth = $totals['Income'];
        $expensesThisMonth = $totals['Expense'];
        $recentTransactions = $transactionRepository->getRecentTransactions();

        return $this->render('home/index.html.twig', [
            'income' => $income,
            'expenses' => $expenses,
            'balance' => $income - $expenses,
            'incomeThisMonth' => $incomeThisMonth,
            'expensesThisMonth' => $expensesThisMonth,
            'balanceThisMonth' => $incomeThisMonth - $expensesThisMonth,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
