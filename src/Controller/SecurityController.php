<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'app_login')]
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils
    ): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_default_index');
        }

        if(!empty($err = $authenticationUtils->getLastAuthenticationError())) {
//            $this->addFlash('error', $err->getMessage());
            $this->addFlash('error', 'Přihlášení se nezdařilo. Zkontrolujte si prosím své přihlašovací údaje.');
        }

        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        return $this->render('security/login.twig', [
            'loginForm' => $form->createView()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
