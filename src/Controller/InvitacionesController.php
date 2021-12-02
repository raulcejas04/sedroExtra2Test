<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Invitacion;
use App\Form\InvitacionType;

#[Route('/dashboard/invitaciones/',)]
class InvitacionesController extends AbstractController
{
    #[Route('mis-invitaciones', name: 'mis_invitaciones')]
    public function misInvitaciones(): Response
    {
        $invitaciones = $this->getDoctrine()->getRepository(Invitacion::class)->findAll();
        $response = $this->renderView('invitaciones/misInvitaciones.html.twig', [
            'invitaciones' => $invitaciones,
        ]);

        return new Response($response);
    }

    #[Route('nueva-invitacion', name: 'nueva_invitacion')]
    public function nuevaInvitacion(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();



        $invitacion = new Invitacion();
        $form = $this->createForm(InvitacionType::class, $invitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $origen = $this->getUser()->getPersonaFisica();

            $invitacion->setOrigen($origen);
            $entityManager->persist($invitacion);
            $entityManager->flush();

            return $this->redirectToRoute('mis_invitaciones');
        }
        $response = $this->renderView('invitaciones/invitacion.html.twig', [
            'form' => $form->createView(),
        ]);
        
        return new Response($response);
    }


}
