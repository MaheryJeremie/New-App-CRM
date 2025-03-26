<?php

namespace App\Controller;

use App\Service\SeuilLimiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeuilLimiteController extends BaseController
{
    private $seuilLimiteService;

    public function __construct(SeuilLimiteService $seuilLimiteService,RequestStack $requestStack)
    {
        $this->seuilLimiteService = $seuilLimiteService;
        parent::__construct($requestStack);
        
    }

    #[Route('/configuration/taux', name: 'seuil_limite_configuration')]
    public function index(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        $seuilLimite = $this->seuilLimiteService->getSeuilLimite();

        /* if (!$seuilLimite) {
            throw $this->createNotFoundException('Seuil limite non trouvé');
        } */

        return $this->render('seuil_limite/index.html.twig', [
            'seuilLimite' => $seuilLimite,
        ]);
    }

    #[Route('/configuration/taux/save', name: 'seuil_limite_modifier',methods: ['GET','POST'])]

    public function modifier(Request $request): Response{
    
    $seuilLimite = $this->seuilLimiteService->getSeuilLimite();

    if ($request->isMethod('POST')) {
        $taux = floatval($request->request->get('pourcentage'));
        
        // Vérification si le seuil existe déjà
        if ($seuilLimite !== null && isset($seuilLimite['id'])) {
            $updatedSeuilLimite = $this->seuilLimiteService->updateSeuilLimite($seuilLimite['id'], $taux);
        } else {
            $updatedSeuilLimite = $this->seuilLimiteService->saveSeuilLimite($taux);
        }

        if ($updatedSeuilLimite) {
            $this->addFlash('success', 'Le taux d\'alerte a été mis à jour avec succès.');
            return $this->redirectToRoute('seuil_limite_configuration');
        } else {
            $this->addFlash('error', 'Une erreur s\'est produite lors de la mise à jour du taux d\'alerte.');
        }
    }

    return $this->render('seuil_limite/modifier.html.twig', [
        'seuilLimite' => $seuilLimite,
    ]);
}

    #[Route('/configuration/taux/all', name: 'seuil_limite_historique')]

    public function getHistorique(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }
        $seuilLimite = $this->seuilLimiteService->getAllSeuilLimite();

        if (!$seuilLimite) {
            throw $this->createNotFoundException('Seuil limite non trouvé');
        }

        return $this->render('seuil_limite/historique.html.twig', [
            'all' => $seuilLimite,
        ]);
    }

}