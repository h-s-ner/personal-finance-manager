<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryDeleteType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\TransactionRepository;
use App\Service\CategoryDeletionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
final class CategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_category_delete', methods: ['GET','POST'])]
    public function delete(
        Request $request,
        Category $category,
        TransactionRepository $transactionRepository,
        CategoryDeletionService $categoryDeletionService): Response
    {       
        $transactionsCount = $transactionRepository->countCategoryTransactions($category);
        $form = $this->createForm(CategoryDeleteType::class, null, [
            'category' => $category,
            'transactionsCount' => $transactionsCount
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $replacementCategory = null;
            if ($form->has('replacementCategory')) {
                $replacementCategory = $form->get('replacementCategory')->getData();
            }
            $categoryDeletionService->delete(
                $category,
                $replacementCategory
            );
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/delete.html.twig', [
            'category' => $category,
            'transactionsCount' => $transactionsCount,
            'form' => $form,
        ]);
       
    }
}
