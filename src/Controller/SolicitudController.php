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
use App\Entity\Realm;
use App\Entity\Solicitud;
use App\Entity\User;
use App\Form\NuevaSolicitudType;
use App\Form\PasoDosType;
use App\Form\RechazarSolicitudType;
use App\Form\ReenviarEmailType;
use App\Service\ValidarSolicitudSrv;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SolicitudController extends AbstractController
{
    private $validador;

    public function __construct(ValidarSolicitudSrv $validador)
    {
        $this->validador = $validador;
    }

    #[Route('/dashboard/nueva-solicitud', name: 'nueva-solicitud')]
    public function nuevaSolicitud(Request $request): Response
    {
        $solicitud = new Solicitud;

        $form = $this->createForm(NuevaSolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //Verifica si ya existe una solicitud activa 
            if ($this->verificarSolicitud($solicitud) == true) {
                return $this->redirectToRoute('dashboard');
            }
            //Verifica que en caso de no existir la PF o no existir el usuario, el correo ingresado no exista en la db para no tener problemas en KC.
            if ($this->verificarEmailSolicitud($solicitud) == true) {
                return $this->redirectToRoute('dashboard');
            }

            $validacion = $this->validador->validarSolicitud($solicitud, $this->getParameter('keycloak_realm'), Solicitud::PASO_UNO);
            if (!$validacion["flagOk"]) {
                $this->addFlash('danger', $validacion["message"]);
                return $this->redirectToRoute('dashboard');
            }

            $solicitud = $validacion["solicitud"];
            $entityManager->persist($solicitud);
            $entityManager->flush();
            $this->addFlash('success', $validacion["message"]);
            return $this->redirectToRoute('dashboard');
        }

        return $this->renderForm('solicitud/paso1.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("dashboard/solicitudes", name="solicitudes")
     */
    public function solicitudes(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $realm = $entityManager->getRepository(Realm::class)->findOneBy(['realm' => $this->getParameter('keycloak_realm')]);
        $solicitudes = $entityManager->getRepository(Solicitud::class)->findSolicitudes($realm, $this->getUser());
        $response = $this->renderView('solicitud\solicitudes.html.twig', [
            'solicitudes' => $solicitudes
        ]);
        return new Response($response);
    }

    /**
     * @Route("solicitud/{hash}/ver", name="verSolicitud")
     */
    public function verSolicitud($hash): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        //TODO: Validar acceso(Realm, usuarioDispositivo)

        $solicitud = $entityManager->getRepository('App\Entity\Solicitud')->findOneBy([
            "hash" => $hash,
            "fechaEliminacion" => null
        ]);

        if (!$solicitud) {
            $this->addFlash('danger', 'La solicitud no existe.');
            return $this->redirectToRoute('solicitudes');
        }

        $response = $this->renderView('solicitud\verSolicitud.html.twig', [
            'solicitud' => $solicitud,
        ]);
        return new Response($response);
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

        if ($solicitud->getFechaExpiracion() < new DateTime()) {
            $this->addFlash('danger', 'La solicitud ha expirado.');
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
            $validacion = $this->validador->validarSolicitud($solicitud, $this->getParameter('keycloak_realm'), Solicitud::PASO_DOS);
            if (!$validacion["flagOk"]) {
                $this->addFlash('danger', $validacion["message"]);
                return $this->redirectToRoute('dashboard');
            }
            $solicitud = $validacion["solicitud"];
            $entityManager->persist($solicitud);
            $entityManager->flush();
            $this->addFlash('success', $validacion["message"]);

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

    /**
     * @Route("dashboard/solicitud/{hash}/aceptar-solicitud", name="aceptarSolicitud")
     */
    public function aceptarSolicitud($hash): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $solicitud = $entityManager->getRepository('App\Entity\Solicitud')->findOneByHash($hash);

        if (!$solicitud->getFechaUso()) {
            $this->addFlash('danger', 'Debe esperar a que el invitado complete los datos para aceptar la solicitud.');
            return $this->redirectToRoute('dashboard');
        }

        $validacion = $this->validador->validarSolicitud($solicitud, $this->getParameter('keycloak_realm'), Solicitud::PASO_TRES);
        if (!$validacion["flagOk"]) {
            $this->addFlash('danger', $validacion["message"]);
            return $this->redirectToRoute('dashboard');
        }
        $solicitud = $validacion["solicitud"];
        $entityManager->persist($solicitud);
        $entityManager->flush();
        $this->addFlash('success', $validacion["message"]);
        return $this->redirectToRoute('dashboard');
    }

    #[Route('/dashboard/solicitud/{hash}/reenviar-email', name: 'solicitud_reenviarEmail')]
    public function reenviarCorreo(Request $request, $hash, MailerInterface $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $solicitud = $entityManager->getRepository('App\Entity\Solicitud')->findOneByHash($hash);

        if (!$solicitud) {
            $this->addFlash('danger', 'La solicitud no se encuentra o no existe');
            return new JsonResponse([
                "status" => "error",
                "html" => $this->renderView('modales/flashAlertsModal.html.twig')
            ]);
        }

        if ($solicitud->getFechaUso()) {
            $this->addFlash('danger', 'Los datos de la solicitud ya han sido completados.');
            return new JsonResponse([
                "status" => "error",
                "html" => $this->renderView('modales/flashAlertsModal.html.twig')
            ]);
        }

        $form = $this->createForm(ReenviarEmailType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->verificarEmailSolicitud($solicitud,false) == true) {
                return new JsonResponse([
                    "status" => "error",
                    "message" => "Error, ya existe un usuario registrado con el Email ingresado. Intente nuevamente con otro Email."
                ]);
            }

            if ($solicitud->getFechaExpiracion() < new DateTime()) {
                $fechaExpiracion = (new DateTime())->modify('+7 days');
                $solicitud->setFechaExpiracion($fechaExpiracion);
            }
            $url = $this->generateUrl('solicitud-paso-2', ["hash" => $solicitud->getHash()], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new TemplatedEmail())
                ->from($this->getParameter('direccion_email_salida'))
                ->to($solicitud->getMail())
                ->subject('Invitación a dispositivo')
                ->htmlTemplate('emails/invitacionPasoUno.html.twig')
                ->context([
                    'nicname' => $solicitud->getNicname(),
                    'url' => $url,
                    'cuil' => $solicitud->getCuil()
                ]);

            $mailer->send($email);

            $entityManager->persist($solicitud);
            $entityManager->flush();

            return new JsonResponse([
                "status" => "success",
                "message" => "Email reenviado con éxito. Se ha enviado un email a " . $solicitud->getMail() . " con instrucciones para completar el registro."
            ]);
        }

        return new JsonResponse([
            "status" => "success",
            "html" => $this->renderView('modales/reenviarEmailModal.html.twig', [
                'solicitud' => $solicitud,
                'formReenviarCorreo' => $form->createView()
            ])
        ]);
    }

    #[Route('/dashboard/solicitud/{hash}/rechazar-solicitud', name: 'solicitud_rechazar')]
    public function rechazarSolicitud(Request $request, $hash, MailerInterface $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $solicitud = $entityManager->getRepository('App\Entity\Solicitud')->findOneByHash($hash);

        if (!$solicitud) {
            $this->addFlash('danger', 'La solicitud no se encuentra o no existe');
            return new JsonResponse([
                "status" => "error",
                "html" => $this->renderView('modales/flashAlertsModal.html.twig')
            ]);
        }

        if ($solicitud->getFechaAlta()) {
            $this->addFlash('danger', 'No se puede rechazar la solicitud porque ya fue dada de alta.');
            return new JsonResponse([
                "status" => "error",
                "html" => $this->renderView('modales/flashAlertsModal.html.twig')
            ]);
        }

        $form = $this->createForm(RechazarSolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitud->setAceptada(false);
            $solicitud->setCorreccion(true);
            $email = (new TemplatedEmail())
                ->from($this->getParameter('direccion_email_salida'))
                ->to($solicitud->getMail())
                ->subject('Solicitud de invitación rechazada')
                ->htmlTemplate('emails/rechazoSolicitud.html.twig')
                ->context([
                    'solicitud' => $solicitud
                ]);

            $mailer->send($email);

            $entityManager->persist($solicitud);
            $entityManager->flush();

            return new JsonResponse([
                "status" => "success",
                "message" => "Solicitud rechazada correctamente. Se ha enviado un correo a " . $solicitud->getMail() . " con el motivo del rechazo."
            ]);
        }

        return new JsonResponse([
            "status" => "success",
            "html" => $this->renderView('modales/rechazarSolicitudModal.html.twig', [
                'solicitud' => $solicitud,
                'formRechazarSolicitud' => $form->createView()
            ])
        ]);
    }

    private function verificarSolicitud($solicitud)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $solicitudActiva = $entityManager->getRepository('App\Entity\Solicitud')->findSolicitudActiva($solicitud->getMail(), $solicitud->getNicname(), $solicitud->getCuit(), $solicitud->getCuil());
        if ($solicitudActiva) {
            if (!$solicitudActiva->getFechaUso()) {
                $this->addFlash('danger', 'Existe una solicitud activa con esos datos. (La persona con CUIT ' . $solicitud->getCuil() . ' aún no envió los datos solicitados)');
            } else {
                $this->addFlash('danger', 'Existe una solicitud activa con esos datos.');
            }

            return true;
        }

        return false;
    }

    private function verificarEmailSolicitud($solicitud, $flash = true)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $realm = $entityManager->getRepository(Realm::class)->findOneBy(["realm" => $this->getParameter('keycloak_realm')]);
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(["username" => $solicitud->getCuil()]);

        if (!$existingUser) {
            $existingEmail = $entityManager->getRepository(User::class)->findOneBy(["email" => $solicitud->getMail(), "realm" => $realm, "fechaEliminacion" => null]);
            if ($existingEmail) {
                if($flash){
                    $this->addFlash('danger', 'Error, ya existe un usuario registrado con el Email ingresado. Intente nuevamente con otro Email.');
                }
                return true;
            }
        }
        return false;
    }
}
