<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\Item;
use App\Entity\User;
use App\Form\AddItemFormType;
use App\Form\AddUserFormType;
use App\Form\ChangeItemFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{

    #[Route('/')]
    public function index(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('default/index.twig', [

        ]);
    }

    #[Route('/users', name: 'app_users')]
    public function users(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $em
    ): Response {
        $user = new User();
        $form = $this->createForm(AddUserFormType::class, $user);

        $userRepository = $em->getRepository(User::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $existing = $userRepository->findOneBy([
                'email' => $user->getEmail()
            ]);

            if(!empty($existing)) {
                $this->addFlash('error', 'There is already an account with this email');
            } else {
                $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));

                $em->persist($user);
                $em->flush();
            }
        }

        return $this->render('default/users.twig', [
            'userForm' => $form->createView(),
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/shopping_list', name: 'app_shopping_list')]
    public function shopping(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $item = new Item();
        $form = $this->createForm(AddItemFormType::class, $item);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'Item added');
        }

        return $this->render('default/shopping.twig', [
            'itemForm' => $form->createView(),
            'denyItemForm' => $this->createForm(ChangeItemFormType::class, null, [
                'action' => $this->generateUrl('app_deny_item')
            ])->createView(),
            'items' => $em->getRepository(Item::class)->findAll()
        ]);
    }

    #[Route('/change_item_state/{itemId}/{state}/{note}', name: 'change_item_state')]
    public function changeItemState(
        int $itemId,
        int $state,
        ?string $note = null,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $item = $em->find(Item::class, $itemId);

        if(empty($item)) {
            throw $this->createNotFoundException();
        }

        $item->setState($state);
        if($note) {
            $item->setNote($note);
        }
        $em->flush();

        return new RedirectResponse($this->generateUrl('app_shopping_list'));
    }

    #[Route('/deny_item', name: 'app_deny_item', methods: ['POST'])]
    public function denyItem(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $item = $em->find(Item::class, $request->request->get('change_item_form')['id']);

        if(empty($item)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ChangeItemFormType::class, $item);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        $this->addFlash('success', 'Item has been denied');
        return new RedirectResponse($this->generateUrl('app_shopping_list'));
    }
}