<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AmbassadorType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/ambassador")
 */
class AmbassadorController extends AbstractController
{
    /**
     * @Route("/", name="ambassador_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('ambassador/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ambassador_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(AmbassadorType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            if ($password) {
                $user->setPassword($passwordEncoder->encodePassword($user, $password));
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('ambassador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ambassador/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ambassador_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('ambassador/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ambassador_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(AmbassadorType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            if ($password) {
                $user->setPassword($passwordEncoder->encodePassword($user, $password));
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ambassador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ambassador/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ambassador_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ambassador_index', [], Response::HTTP_SEE_OTHER);
    }
}
