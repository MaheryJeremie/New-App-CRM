<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseController extends AbstractController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function checkAuthentication(): ?RedirectResponse
    {
        $session = $this->requestStack->getSession();
        $user = $session->get('user');
        if (!$user || !$user['authenticated']) {
            return new RedirectResponse($this->generateUrl('app_login'));
        }

        return null;
    }
}
