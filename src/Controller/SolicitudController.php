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
use App\Form\PasoDosType;

class SolicitudController extends AbstractController
{
    #[Route('public/{hash}/completar-datos', name: 'solicitud-paso-2')]
    public function pasoDos(Request $request, $hash): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $solicitud = $entityManager->getRepository('App:Solicitud')->findOneByHash($hash);
        if (!$solicitud) {
            //TODO: que redirija al home cuando el mismo esté listo
            //TODO: Que no se pueda errar de hash más de 5 veces por IP por hora
            return $this->redirectToRoute('home');
        }
        if ($solicitud->getUsada() == true) {
            $this->addFlash('danger', 'Ya ha ingresado los datos anteriormente para ésta solicitud');
            //TODO: que redirija al home cuando el mismo esté listo
            return $this->redirectToRoute('home');
        }

        $pasoDos = new PasoDos;

        //todo esto de acá abajo hasta el $form es para que el formulario se renderice con datos en readonly

        //Si existe la Persona Fisica, la traigo
        $existingPersonaFisica = $entityManager->getRepository(PersonaFisica::class)->findOneBy(["cuitCuil" => $solicitud->getCuil()]);
        $personaFisica = $existingPersonaFisica ? $existingPersonaFisica : new PersonaFisica;

         //Si existe la Persona Jurídica, la traigo
        $existingPersonaJuridica = $entityManager->getRepository(PersonaJuridica::class)->findOneBy(["cuit" => $solicitud->getCuil()]);
        $personaJuridica = $existingPersonaJuridica ? $existingPersonaJuridica : new PersonaJuridica;

        //Instancia del dispositivo solo para readonly (no se persiste)
        $dispositivo = new Dispositivo;
        $dispositivo->setNicname($solicitud->getNicname());
        $pasoDos->setDispositivo($dispositivo);

        $pasoDos->setPersonaFisica($personaFisica);
        if (!$existingPersonaFisica) {
            $pasoDos->getPersonaFisica()->setCuitCuil($solicitud->getCuil());
        }

        $pasoDos->setPersonaJuridica($personaJuridica);
        if (!$existingPersonaJuridica) {
            $pasoDos->getPersonaJuridica()->setCuit($solicitud->getCuit());
        }

        //$pasoDos->getPersonaJuridica()->getDispositivos()->add($dispositivo);

        $form = $this->createForm(PasoDosType::class, $pasoDos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitud->setPersonaFisica($pasoDos->getPersonaFisica());
            $solicitud->setPersonaJuridica($pasoDos->getPersonaJuridica());
            $solicitud->setFechaUso(new \DateTime('now'));
           // $solicitud->setDispositivo($dispositivo);
            $solicitud->setUsada(true);

            $entityManager->persist($solicitud);
         //   $entityManager->persist($dispositivo);
            $entityManager->flush();

            $this->addFlash('success', 'Datos completados con éxito!');

            //TODO: Que el redirectToRoute vaya al home cuando el mismo esté listo
            return $this->redirectToRoute('home');
        }

        return $this->renderForm('solicitud/paso2.html.twig', [
            'form' => $form,
            'solicitud' => $solicitud,
            'existingPF' => (bool)$existingPersonaFisica,
            'existingPJ' => (bool)$existingPersonaJuridica
        ]);
    }
}
