<?php

namespace App\Controller;

use App\Service\BudgetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends BaseController
{
    private $budgetService;

    public function __construct(BudgetService $budgetApiService,RequestStack $requestStack)
    {
        $this->budgetService = $budgetApiService;
        parent::__construct($requestStack);
    }
    #[Route('/dashboard/budgets', name: 'budgets')]

    public function listBudgetsByClient(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $budgets = $this->budgetService->getAllBudgetsByclient();

            return $this->render('budget/budget.html.twig', [
                'budgets' => $budgets,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
    #[Route('/dashboard/budgets/all', name: 'all_budgets')]

    public function listBudgets(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $budgets = $this->budgetService->getAllBudgets();

            return $this->render('budget/budget.html.twig', [
                'budgets' => $budgets,
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/budget/update', name: 'budget_update', methods: ['POST'])]
    public function updateBudget(Request $request): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }

        $id = (int)$request->request->get('id');
        $valeur = floatval($request->request->get('valeur'));

        try {
            $this->budgetService->updateBudget($id, $valeur);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }

        return $this->redirectToRoute('budgets');
    }

    #[Route('/budget/delete/{id}', name: 'budget_delete',methods:['POST'])]

    public function delete(int $id): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        try {
            $this->budgetService->deleteBudget($id);

            return $this->redirectToRoute('budgets');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}