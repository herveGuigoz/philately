<?php

namespace App\Controller;

use App\Manager\TransactionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(TransactionManager $transactionManager): Response
    {
        $result = $transactionManager->getTotalResult();

        return $this->render('dashboard/index.html.twig', [
            'result' => $result,
        ]);
    }
}
