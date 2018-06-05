<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method("GET")
     */
    public function login(AuthenticationUtils $authUtils): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('home');
        }

        $error        = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @Method("GET")
     */
    public function logout(): void
    {
        throw new \Exception('Logout error!');
    }
}