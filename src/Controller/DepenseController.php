<?php

namespace App\Controller;

use App\Service\DepenseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepenseController extends BaseController
{
    private $expenseApiService;

    public function __construct(DepenseService $expenseApiService,RequestStack $requestStack)
    {
        $this->expenseApiService = $expenseApiService;
        parent::__construct($requestStack);
    }
    #[Route('/expense/update/{id}', name: 'expense_update',methods:['POST'])]

    public function update(int $id, Request $request): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $data = ['montant' => $request->request->get('montant')];
            $this->expenseApiService->updateExpense($id, $data);

            return $this->redirectToRoute('expenses');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/expense/delete/{id}', name: 'expense_delete',methods:['POST'])]

    public function delete(int $id): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $this->expenseApiService->deleteExpense($id);

            return $this->redirectToRoute('expenses');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
    #[Route('/expense', name: 'expenses')]
    public function listExpenses(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $expenses = $this->expenseApiService->getAllExpenses();

            return $this->render('depense/depense.html.twig', [
                'expenses' => $expenses,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}