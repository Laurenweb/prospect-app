<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Form\ProspectType;
use App\Repository\ProspectRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prospect")
 */
class ProspectController extends AbstractController
{
    /**
     * @Route("/", name="prospect_index", methods={"GET"})
     */
    public function index(ProspectRepository $prospectRepository): Response
    {
        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospectRepository->findBy([
                'isVisible' => true
            ]),
        ]);
    }

    /**
     * @Route("/new", name="prospect_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $prospect = new Prospect();
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prospect->setReporter($this->getUser());
            $prospect->setIsVisible(true);
            $prospect->setCreatedAt(new DateTimeImmutable());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prospect);
            $entityManager->flush();

            return $this->redirectToRoute('prospect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prospect/new.html.twig', [
            'prospect' => $prospect,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="prospect_show", methods={"GET"})
     */
    public function show(Prospect $prospect): Response
    {
        return $this->render('prospect/show.html.twig', [
            'prospect' => $prospect,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prospect_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prospect $prospect): Response
    {
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prospect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prospect/edit.html.twig', [
            'prospect' => $prospect,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="prospect_delete", methods={"POST"})
     */
    public function delete(Request $request, Prospect $prospect): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prospect->getId(), $request->request->get('_token'))) {
            $prospect->setIsVisible(false);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('prospect_index', [], Response::HTTP_SEE_OTHER);
    }
}
