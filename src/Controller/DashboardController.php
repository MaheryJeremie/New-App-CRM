<?php

namespace App\Controller;

use App\Service\BudgetService;
use App\Service\LeadService;
use App\Service\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;


class DashboardController extends BaseController
{
    private $leadService;
    private $ticketService;
    private $budgetService;

    public function __construct(LeadService $leadService,TicketService $ticketService,BudgetService $budgetService,RequestStack $requestStack)
    {
        $this->leadService = $leadService;
        $this->ticketService = $ticketService;
        $this->budgetService = $budgetService;
        parent::__construct($requestStack);
    }
    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $totalExpensesLead = $this->leadService->getTotalExpenses();
            $allExpenseLead = $this->leadService->getAllLeadExpenses();
            $totalExpensesTicket = $this->ticketService->getTotalExpenses();
            $allExpenseTicket = $this->ticketService->getAllTicketExpenses();
            $budgetTotal = $this->budgetService->getTotalBudget();
            $allBudget = $this->budgetService->getAllBudgetsByclient();

            return $this->render('dashboard/index.html.twig', [
                'totalExpensesLead' => $totalExpensesLead,
                'leads' => $allExpenseLead,
                'totalExpensesTicket' => $totalExpensesTicket,
                'tickets' => $allExpenseTicket,
                'budgetTotal' => $budgetTotal,
                'allBudgets' => $allBudget,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}