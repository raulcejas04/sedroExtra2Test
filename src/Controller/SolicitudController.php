<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Representacion;
use App\Entity\PersonaJuridica;
use App\Entity\PersonaFisica;
use App\Entity\Dispositivo;
use App\Form\RepresentacionType;

class SolicitudController extends AbstractController
{
    #[Route('solicitud/{hash}/completar-datos', name: 'solicitud-paso-2')]
    public function pasoDos(Request $request, $hash): Response
    {        
        $entityManager = $this->getDoctrine()->getManager();
        $solicitud = $entityManager->getRepository('App:Solicitud')->findOneByHash($hash);
        if (!$solicitud){
            //TODO: que redirija al home cuando el mismo esté listo
            //TODO: Que no se pueda errar de hash más de 5 veces por IP por hora
            return $this->redirectToRoute('dashboard');
        }
        if ($solicitud->getUsada() == true){
            $this->addFlash('danger', 'Ya ha ingresado los datos anteriormente para ésta solicitud');
            //TODO: que redirija al home cuando el mismo esté listo
            return $this->redirectToRoute('dashboard');
        }
        
        $representacion = new Representacion;
        
        //todo esto de acá abajo hasta el $form es para que el formulario se renderice con datos en readonly
        $personaFisica = new PersonaFisica;
        $personaJuridica = new PersonaJuridica;        
        $dispositivo = new Dispositivo;

        $dispositivo->setNicname($solicitud->getNicname());
        
        $representacion->setPersonaFisica($personaFisica);
        $representacion->getPersonaFisica()->setCuitCuil($solicitud->getCuil());
        
        $representacion->setPersonaJuridica($personaJuridica);
        $representacion->getPersonaJuridica()->setCuit($solicitud->getCuit());
        
        $representacion->getPersonaJuridica()->getDispositivos()->add($dispositivo);
        
        $form = $this->createForm(RepresentacionType::class, $representacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitud->setPersonaFisica($representacion->getPersonaFisica());
            $solicitud->setPersonaJuridica($representacion->getPersonaJuridica());
            $solicitud->setFechaUso(new \DateTime('now'));
            $solicitud->setDispositivo($dispositivo);
            $solicitud->setUsada(true);
            
            $dispositivo->setPersonaJuridica($personaJuridica);

            $entityManager->persist($representacion);            
            $entityManager->persist($solicitud);
            $entityManager->persist($dispositivo);
            $entityManager->flush();
            
            $this->addFlash('success', 'Datos completados con éxito!');

            //TODO: Que el redirectToRoute vaya al home cuando el mismo esté listo
            return $this->redirectToRoute('dashboard');
        }

        return $this->renderForm('solicitud/paso2.html.twig', [
            'form' => $form,
            'solicitud' => $solicitud
        ]);
    }
}
