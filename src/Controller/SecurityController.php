<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /*
    * @Route("/login", name="security_login")
    */
    public function login(AuthenticationUtils $authenticationUtils, FormFactoryInterface $factory)
    {
        $form = $this->createForm(LoginType::class, ['email' => $authenticationUtils->getLastUsername()]);
        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }
    /*
    * @Route("/logout", name="security_logout")
    */
    public function logout()
    {
    }
}
