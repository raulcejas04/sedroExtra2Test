<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

use App\Entity\Invitacion;
use App\Entity\PersonaFisica;
use App\Form\InvitacionType;
use DateTime;

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
    public function nuevaInvitacion(Request $request,MailerInterface $mailer): Response
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
                $persona->setNombres($invitacion->getPersonaFisica()->getNombres());
                $persona->setApellido($invitacion->getPersonaFisica()->getApellido());
                $persona->setTipoCuitCuil($invitacion->getPersonaFisica()->getTipoCuitCuil());
                $persona->setSexo($invitacion->getPersonaFisica()->getSexo());
                $persona->setNacionalidad($invitacion->getPersonaFisica()->getNacionalidad());
                $persona->setTipoDocumento($invitacion->getPersonaFisica()->getTipoDocumento());
                $persona->setNroDoc($invitacion->getPersonaFisica()->getNroDoc());
                //TODO: Crear usuario en keycloak y asignar valores al usuario.
               
               // $persona->setEmail($invitacion->getPersonaFisica()->getEmail());
               // $persona->setTelefono($invitacion->getPersonaFisica()->getTelefono());
                
               //TODO: Asignar grupo segun corresponda
                
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

            
            $email = (new TemplatedEmail())
            ->from($this->getParameter('direccion_email_salida'))
            ->to('target@correo.com')
            ->subject('Invitación a Dispositivo')            
            ->htmlTemplate('emails/invitacionDispositivo.html.twig')
            ->context([
                'nombre' => $persona->__toString(),
                'dispositivo'=>$invitacion->getDispositivo()->getNicname(),
                'hash' => $invitacion->getHash()
            ]);
            
            $mailer->send($email);

            dd($invitacion);
            $invitacion->setOrigen($origen);
            $invitacion->prePersist();
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
