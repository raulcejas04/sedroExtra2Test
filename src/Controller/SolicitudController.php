<?php

namespace App\Controller;

use App\AuxEntities\PasoDos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PersonaJuridica;
use App\Entity\PersonaFisica;
use App\Entity\Dispositivo;
use App\Entity\DispositivoResponsable;
use App\Entity\Solicitud;
use App\Form\PasoDosType;
use App\Service\ValidarSolicitudSrv;
use DateTime;

class SolicitudController extends AbstractController
{
    private $validador;

    public function __construct(ValidarSolicitudSrv $validador)
    {
        $this->validador = $validador;
    }

    #[Route('public/{hash}/completar-datos', name: 'solicitud-paso-2')]
    public function pasoDos(Request $request, $hash): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $solicitud = $entityManager->getRepository('App:Solicitud')->findOneByHash($hash);
        if (!$solicitud) {
            $this->addFlash('danger', 'La solicitud no existe o no se encuentra.');
            //TODO: que redirija al home cuando el mismo esté listo
            //TODO: Que no se pueda errar de hash más de 5 veces por IP por hora}
            return $this->redirectToRoute('home');
        }
        if ($solicitud->getUsada() == true) {
            $this->addFlash('danger', 'Ya ha ingresado los datos anteriormente para ésta solicitud');
            //TODO: que redirija al home cuando el mismo esté listo
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(PasoDosType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           /*  $solicitud->setPersonaFisica($pasoDos->getPersonaFisica());
            $solicitud->setPersonaJuridica($pasoDos->getPersonaJuridica()); */
        
            $validacion = $this->validador->validarSolicitud($solicitud, $this->getParameter('keycloak_realm'), Solicitud::PASO_DOS);
            if (!$validacion["flagOk"]) {
                $this->addFlash('danger', $validacion["message"]);
                return $this->redirectToRoute('dashboard');
            }

            $solicitud = $validacion["solicitud"];
            $entityManager->persist($solicitud);
            $entityManager->flush();          
            $this->addFlash('success', $validacion["message"]);

            $this->addFlash('success', 'Datos completados con éxito!');

            //TODO: Que el redirectToRoute vaya al home cuando el mismo esté listo
            return $this->redirectToRoute('home');
        }

        return $this->renderForm('solicitud/paso2.html.twig', [
            'form' => $form,
            'solicitud' => $solicitud,
            'existingPF' => (bool)$solicitud->getPersonaFisica(),
            'existingPJ' => (bool)$solicitud->getPersonaJuridica()
        ]);
    }
}
