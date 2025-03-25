<?php

namespace App\Controller;

use App\Service\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends BaseController
{
    private $leadService;

    public function __construct(TicketService $leadService,RequestStack $requestStack)
    {
        $this->leadService = $leadService;
        parent::__construct($requestStack);
    }

    #[Route('/ticket/expenses', name: 'ticket_expenses', methods: ['GET'])]
    public function listLeadExpenses(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $expenses = $this->leadService->getAllTicketExpenses();

            return $this->render('ticket/expenses.html.twig', [
                'expenses' => $expenses,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
    #[Route('/ticket/expenses', name: 'ticket_expenses_valide', methods: ['GET'])]
    public function listLeadExpensesValide(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $expenses = $this->leadService->getAllTicketExpensesValide();

            return $this->render('ticket/expenses.html.twig', [
                'expenses' => $expenses,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/ticket/expense/update/{id}', name: 'ticket_expense_update', methods: ['POST'])]
    public function updateLeadExpense(int $id, Request $request): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $data = ['montant' => $request->request->get('montant')];
            $this->leadService->updateExpense($id, $data);

            return $this->redirectToRoute('ticket_expenses');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/ticket/expense/delete/{id}', name: 'ticket_expense_delete', methods: ['POST'])]
    public function deleteLeadExpense(int $id): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $this->leadService->deleteExpense($id);

            return $this->redirectToRoute('ticket_expenses');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}