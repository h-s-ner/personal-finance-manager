<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(TransactionRepository $transactionRepository, Request $request): Response
    {  
        $search = $request->query->get('search');
        if($search==null)
        {
            return $this->render('transaction/index.html.twig', [
                'transactions' => $transactionRepository->findAll(),
            ]);
        }
        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactionRepository->search($search),
        ]);
    }
}
