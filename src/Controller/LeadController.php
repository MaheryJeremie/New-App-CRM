<?php

namespace App\Controller;

use App\Service\LeadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeadController extends BaseController
{
    private $leadService;

    public function __construct(LeadService $leadService,RequestStack $requestStack)
    {
        $this->leadService = $leadService;
        parent::__construct($requestStack);

    }

    #[Route('/lead/expenses', name: 'lead_expenses', methods: ['GET'])]
    public function listLeadExpenses(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $expenses = $this->leadService->getAllLeadExpenses();

            return $this->render('lead/expenses.html.twig', [
                'expenses' => $expenses,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
    #[Route('/lead/expenses', name: 'lead_expenses_valide', methods: ['GET'])]
    public function listLeadExpensesValide(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $expenses = $this->leadService->getAllLeadExpensesValide();

            return $this->render('lead/expenses.html.twig', [
                'expenses' => $expenses,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/lead/expense/update', name: 'lead_expense_update', methods: ['POST'])]
    public function updateLeadExpense(Request $request): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $id=(int)$request->request->get('id');
            $data = ['montant' => (float)$request->request->get('montant')];
            $this->leadService->updateExpense($id, $data);

            return $this->redirectToRoute('lead_expenses');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/lead/expense/delete/{id}', name: 'lead_expense_delete', methods: ['POST'])]
    public function deleteLeadExpense(int $id): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $this->leadService->deleteExpense($id);

            return $this->redirectToRoute('lead_expenses');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}