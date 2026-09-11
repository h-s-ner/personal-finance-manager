<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(
        TransactionRepository $transactionRepository,
        Request $request
    ): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $search = $data['search'];
            if ($search == null || $search == '')
            {
                return $this->redirectToRoute('app_transaction_index');
            }

            $transactions = $transactionRepository->search($search);
        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
            'transactions' => $transactions??[],
        ]);
    }
}
