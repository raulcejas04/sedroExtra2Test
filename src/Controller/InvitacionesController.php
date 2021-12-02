<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Invitacion;
use App\Entity\PersonaFisica;
use App\Form\InvitacionType;
use App\Entity\PersonaFisica;


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
        $invitacion = new Invitacion();
        $form = $this->createForm(InvitacionType::class, $invitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $cuitInvitado = $invitacion->getPersonaFisica()->getCuitCuil();
            $persona = $this->getDoctrine()->getRepository(PersonaFisica::class)->findOneBy(['cuitCuil' => $cuitInvitado]);

            if ($persona == null) {
                $persona = new PersonaFisica();
                $persona->setCuitCuil($cuitInvitado);
                $persona->setNombre($invitacion->getPersonaFisica()->getNombre());
                $persona->setApellido($invitacion->getPersonaFisica()->getApellido());
                $persona->setEmail($invitacion->getPersonaFisica()->getEmail());
                $persona->setTelefono($invitacion->getPersonaFisica()->getTelefono());
                $entityManager->persist($persona);
            } else {
                $invitacion->setPersonaFisica($persona);
            }


            $origen = $this->getUser()->getPersonaFisica();

            if ($invitacion->getPersonaFisica()->getId() == $origen->getId()) {
                $this->addFlash('error', 'No puedes invitarte a ti mismo.');
                return $this->redirectToRoute('nueva_invitacion');
            }

            if ($this->getDoctrine()->getRepository(Invitacion::class)->findOneBy(['personaFisica' => $invitacion->getPersonaFisica(), 'dispositivo' => $invitacion->getDispositivo()])) {
                $this->addFlash('error', 'Ya has enviado una invitación a esta persona.');
                return $this->redirectToRoute('nueva_invitacion');
            }

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
