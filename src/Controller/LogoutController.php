<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        // Récupérer la session via RequestStack
        $session = $this->requestStack->getSession();
        
        // Invalider complètement la session
        $session->invalidate();
        
        // Effacer spécifiquement les données utilisateur
        $session->remove('user');
        
        // Rediriger vers la page de login
        return $this->redirectToRoute('app_login');
    }
}