<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\RememberMe\ResponseListener;

class SecurityController extends AbstractController
{
    #[Route('/login_check', name: 'login_check')]
    public function check(): never
    {
        throw new \LogicException('This code should never be reached');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $roles = [];

        if ($this->getUser()) {
            $roles = $this->getUser()->getRoles();
        }

        if (in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('admin'); // Redirect admin to /products
        } elseif (in_array('ROLE_USER', $roles)) {
            return $this->redirectToRoute('app_user_access'); // Redirect user to /user
        } elseif (in_array('IS_AUTHENICATED_USER', $roles)) {
            return $this->redirectToRoute('app_login'); // Redirect user to /user
        }



        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    // public function requestLoginLink(LoginLinkHandlerInterface $loginLinkHandler, UserRepository $userRepository, Request $request): Response
    // {
    //     // check if login form is submitted
    //     if ($request->isMethod('POST')) {
    //         // load the user in some way (e.g. using the form input)
    //         $email = $request->request->get('email0');
    //         $user = $userRepository->findOneBy(['email' => $email]);

    //         // create a login link for $user this returns an instance
    //         // of LoginLinkDetails
    //         $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
    //         $loginLink = $loginLinkDetails->getUrl();

    //         // ... send the link and return a response (see next section)
    //     }

    //     // if it's not submitted, render the "login" form
    //     return $this->render('security/login.html.twig');
    // }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(Security $security, SessionInterface $session): Response
    {
        // Invalidate the session to log the user out
        $session->invalidate();
        $session = $security->logout();

        // Create a response with the logout success handler
        return $this->redirectToRoute('app_login');
    }
}
