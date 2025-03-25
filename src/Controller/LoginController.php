<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoginController extends BaseController
{
    private HttpClientInterface $httpClient;
    private RequestStack $requestStack;

    public function __construct(HttpClientInterface $httpClient,RequestStack $requestStack)
    {
        $this->httpClient = $httpClient;
        $this->requestStack = $requestStack;
    }

    #[Route('/', name: 'app_home')]
    public function homeRedirect(): Response
    {
        $authCheck = $this->checkAuthentication();
        if ($authCheck) {
            return $authCheck;
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/login', name: 'app_login', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('login/index.html.twig');
    }

    #[Route('/login', name: 'app_login_post', methods: ['POST'])]
    public function loginPost(Request $request): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $response = $this->httpClient->request('POST', 'http://localhost:8080/api/login', [
            'body' => [
                'username' => $username,
                'password' => $password,
            ]
        ]);

        $isAuthenticated = json_decode($response->getContent(), true);
        if ($isAuthenticated) {
            $session=$this->requestStack->getSession();
            $session->set('user', [
                'username' => $username,
                'authenticated' => true,
            ]);

            return $this->redirectToRoute('dashboard');
        }


        if ($isAuthenticated) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('login/index.html.twig', [
            'error' => 'Identifiants invalides.',
        ]);
    }
}