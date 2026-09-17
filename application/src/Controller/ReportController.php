<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Form\ReportFilterType;
use App\Service\ReportService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController
{
    #[Route('/report/daily', name: 'app_report_daily')]
    public function daily_report(
        CategoryRepository $categoryRepository,
        Request $request
    ): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, [
            'period' => 'day',
        ]);

        $form->handleRequest($request);

        $from = new \DateTime();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $from = $data['date'];
        }
        $to =  clone $from;
        $to->modify('+1 day');        

        return $this->render('report/daily.html.twig', [
            'categories' => $categoryRepository->findCategoriesForReport($from, $to),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/report/monthly', name: 'app_report_monthly')]
    public function monthly_report(
        CategoryRepository $categoryRepository,
        ReportService $reportService,
        Request $request
    ): Response
    {
        $years = $reportService->getAvailableYears();
        $form = $this->createForm(ReportFilterType::class, null, [
            'period' => 'month',
            'years' => $years
        ]);

        $form->handleRequest($request);

        $from = new \DateTime('first day of this month 00:00:00');
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $from = new \DateTime(
                "first day of {$data['month']} {$data['year']} 00:00:00"
            );
        }
        $to =  clone $from;
        $to->modify('+1 month');

        return $this->render('report/monthly.html.twig', [
            'categories' => $categoryRepository->findCategoriesForReport($from, $to),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/report/yearly', name: 'app_report_yearly')]
    public function yearly_report(
        CategoryRepository $categoryRepository,
        ReportService $reportService,
        Request $request
    ): Response
    {
        $years = $reportService->getAvailableYears();
        $form = $this->createForm(ReportFilterType::class, null, [
            'period' => 'year',
            'years' => $years
        ]);
        $form->handleRequest($request);
        $year = date('Y');      
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $year = $data['year'];
        }
        $from = new \DateTime($year . '-01-01 00:00:00');
        $to = new \DateTime(($year + 1) . '-01-01 00:00:00');
        return $this->render('report/yearly.html.twig', [
            'categories' => $categoryRepository->findCategoriesForReport($from, $to),
            'form' => $form->createView(),
        ]);
    }
}
