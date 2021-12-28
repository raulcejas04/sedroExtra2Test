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
use App\Entity\Realm;
use App\Entity\User;
use App\Entity\UsuarioDispositivo;
use App\Form\InvitacionType;
use App\Form\ReenviarEmailType;
use App\Service\IntranetService;
use App\Service\KeycloakApiSrv;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/dashboard/invitaciones/',)]
class InvitacionesController extends AbstractController
{

    private $intranetService;

    public function __construct(IntranetService $srv)
    {
        $this->intranetService = $srv;
    }


    #[Route('ver-invitacion/{hash}', name: 'ver_invitacion')]
    public function verInvitacion($hash): Response
    {
        $invitacion = $this->getDoctrine()->getManager()->getRepository(Invitacion::class)->findOneBy([
            "hash" => $hash,
            "fechaEliminacion" => null
        ]);

        if (!$invitacion) {
            $this->addFlash('danger', 'La invitación no se encuentra o no existe.');
            return $this->redirectToRoute('dashboard');
        }

        $response = $this->renderView('invitaciones/verInvitacion.html.twig', [
            'invitacion' => $invitacion,
        ]);

        return new Response($response);
    }

    #[Route('mis-invitaciones', name: 'mis_invitaciones')]
    public function misInvitaciones(): Response
    {
        /* $invitaciones = $this->getDoctrine()->getRepository(Invitacion::class)->findBy([
            "fechaEliminacion" => null,
            "origen" => $this->getUser()->getPersonaFisica()
        ]); */

        $invitaciones = $this->getDoctrine()->getRepository(Invitacion::class)->findInvitacionesByUser($this->getUser());

        $response = $this->renderView('invitaciones/misInvitaciones.html.twig', [
            'invitaciones' => $invitaciones,
        ]);

        return new Response($response);
    }

    #[Route('nueva-invitacion', name: 'nueva_invitacion')]
    public function nuevaInvitacion(Request $request, MailerInterface $mailer): Response
    {
        $invitacion = new Invitacion();
        $form = $this->createForm(InvitacionType::class, $invitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $cuitInvitado = $invitacion->getPersonaFisica()->getCuitCuil();
            $persona = $this->getDoctrine()->getRepository(PersonaFisica::class)->findOneBy(['cuitCuil' => $cuitInvitado]);

            //TODO:Validar que el usuario no tenga una invitación al dispositivo pendiente.
            //TODO: Validar que el usuario no se encuentre ya asociado al dispositivo.

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
                $persona->setEstadoCivil($invitacion->getPersonaFisica()->getEstadoCivil());
                $entityManager->persist($persona);
            }
            $invitacion->setPersonaFisica($persona);
            $origen = $this->getUser()->getPersonaFisica();

            if ($invitacion->getPersonaFisica()->getId() == $origen->getId()) {
                $this->addFlash('danger', 'No puedes invitarte a ti mismo.');
                return $this->redirectToRoute('nueva_invitacion');
            }

            if ($this->getDoctrine()->getRepository(Invitacion::class)->findOneBy(['personaFisica' => $invitacion->getPersonaFisica(), 'dispositivo' => $invitacion->getDispositivo()])) {
                $this->addFlash('danger', 'Ya has enviado una invitación a esta persona.');
                return $this->redirectToRoute('nueva_invitacion');
            }
            //  dd($invitacion);
            $invitacion->setOrigen($origen);
            $invitacion->prePersist();
            $entityManager->persist($invitacion);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from($this->getParameter('direccion_email_salida'))
                ->to($form['email']->getData())
                ->subject('Invitación a Dispositivo')
                ->htmlTemplate('emails/invitacionDispositivo.html.twig')
                ->context([
                    'nombre' => $persona->__toString(),
                    'dispositivo' => $invitacion->getDispositivo()->getNicname(),
                    'hash' => $invitacion->getHash()
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('mis_invitaciones');
        }
        $response = $this->renderView('invitaciones/invitacion.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($response);
    }

    #[Route('aceptar-invitacion/{hash}', name: 'aceptar_invitacion')]
    public function AceptarInvitacion($hash, MailerInterface $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $invitacion = $em->getRepository(Invitacion::class)->findOneBy([
            "hash" => $hash,
            "fechaEliminacion" => null
        ]);

        if (!$invitacion) {
            $this->addFlash('danger', 'La invitación no se encuentra o no existe.');
            return $this->redirectToRoute('dashboard');
        }

        if ($invitacion->getFechaUso() && $invitacion->getAceptada()) {
            $this->addFlash('danger', 'La invitación se encuentra aceptada.');
            return $this->redirectToRoute('dashboard');
        }

        if ($invitacion->getFechaUso() && $invitacion->getAceptada() == false) {
            $this->addFlash('danger', 'La invitación se encuentra rechazada.');
            return $this->redirectToRoute('dashboard');
        }

        if ($invitacion->getFechaEliminacion()) {
            $this->addFlash('danger', 'La invitación fue eliminada.');
            return $this->redirectToRoute('dashboard');
        }

        $invitacion->setFechaUso(new DateTime());
        $invitacion->setAceptada(true);
        $persona = $invitacion->getPersonaFisica();

        $realm = $em->getRepository(Realm::class)->findOneBy(["realm" => $this->getParameter('keycloak_realm')]);
        $existingUser = $em->getRepository(User::class)->findByUserUsernameAndRealm($persona->getCuitCuil(), $realm);

        if (!$existingUser) {
            //Crea usuario en keycloak y en la tabla usuarios
            $existingUser = $this->crearUsuarioEnKeycloak($persona, $invitacion->getEmail(), $mailer);
        }

        //Asignamos el usuario al dispositivo
        $dispositivo = $invitacion->getDispositivo();
        $usuarioDispositivo = new UsuarioDispositivo();
        $usuarioDispositivo->setDispositivo($dispositivo);
        $usuarioDispositivo->setUsuario($existingUser);
        //TODO:Inyectar este seteo mediante un servicio o función
        $usuarioDispositivo->setFechaAlta(new DateTime());
        $dispositivo->addUsuarioDispositivo($usuarioDispositivo);

        //TODO: No hardcodear, traer desde dispositivo->tipoDispositivo->grupos
        //Hardcodeado para testear
        $groups = ["Administradores"];
        $this->asignarGrupos($existingUser, $groups);

        $em->persist($invitacion);
        $em->flush();

        $this->addFlash('success', "Invitación confirmada correctamente. Se ha enviado un email a {$invitacion->getEmail()} con los datos de acceso.");
        return $this->redirectToRoute('dashboard');
    }

    #[Route('rechazar-invitacion/{hash}', name: 'rechazar_invitacion')]
    public function RechazarInvitacion($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $invitacion = $em->getRepository(Invitacion::class)->findOneBy([
            "hash" => $hash,
            "fechaEliminacion" => null
        ]);

        if (!$invitacion) {
            $this->addFlash('danger', 'La invitación no se encuentra o no existe.');
            return $this->redirectToRoute('dashboard');
        }

        if ($invitacion->getFechaUso() && $invitacion->getAceptada() == false) {
            $this->addFlash('danger', 'La invitación se encuentra rechazada.');
            return $this->redirectToRoute('dashboard');
        }

        if ($invitacion->getFechaEliminacion()) {
            $this->addFlash('danger', 'La invitación fue eliminada.');
            return $this->redirectToRoute('dashboard');
        }

        $invitacion->setFechaUso(new DateTime());
        $invitacion->setAceptada(false);

        $em->persist($invitacion);
        $em->flush();

        $this->addFlash('success', 'Invitación rechazada correctamente.');
        return $this->redirectToRoute('dashboard');
    }

    #[Route('eliminar-invitacion/{hash}', name: 'eliminar_invitacion')]
    public function EliminarInvitacion($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $invitacion = $em->getRepository(Invitacion::class)->findOneBy([
            "hash" => $hash,
            "fechaEliminacion" => null
        ]);

        if (!$invitacion) {
            $this->addFlash('danger', 'La invitación no se encuentra o no existe.');
            return $this->redirectToRoute('dashboard');
        }

        if ($invitacion->getFechaUso() && $invitacion->getAceptada() == false) {
            $this->addFlash('danger', 'La invitación se encuentra rechazada.');
            return $this->redirectToRoute('dashboard');
        }

        $invitacion->setFechaEliminacion(new DateTime());
        $em->persist($invitacion);
        $em->flush();

        $this->addFlash('success', 'Invitación eliminada correctamente.');
        return $this->redirectToRoute('dashboard');
    }

    #[Route('reenviar-email/{hash}', name: 'invitacion_reenviarEmail')]
    public function ReenviarEmail(Request $request, $hash, MailerInterface $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $invitacion = $em->getRepository(Invitacion::class)->findOneBy([
            "hash" => $hash,
            "fechaEliminacion" => null
        ]);
        if (!$invitacion) {
            $this->addFlash('danger', 'La invitación no se encuentra o no existe');
            return new JsonResponse([
                "status" => "error",
                "html" => $this->renderView('modales/flashAlertsModal.html.twig')
            ]);
        }

        if ($invitacion->getFechaUso()) {
            $this->addFlash('danger', 'Los datos de la invitación ya han sido completados');
            return new JsonResponse([
                "status" => "error",
                "html" => $this->renderView('modales/flashAlertsModal.html.twig')
            ]);
        }

        $form = $this->createForm(ReenviarEmailType::class, $invitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new TemplatedEmail())
                ->from($this->getParameter('direccion_email_salida'))
                ->to($invitacion->getEmail())
                ->subject('Invitación a Dispositivo')
                ->htmlTemplate('emails/invitacionDispositivo.html.twig')
                ->context([
                    'nombre' => $invitacion->getPersonaFisica()->__toString(),
                    'dispositivo' => $invitacion->getDispositivo()->getNicname(),
                    'hash' => $invitacion->getHash()
                ]);

            $mailer->send($email);

            $em->persist($invitacion);
            $em->flush($invitacion);

            return new JsonResponse([
                "status" => "success",
                "message" => "Email reenviado con éxito. Se ha enviado un email a " . $invitacion->getEmail() . " con la invitación al dispositivo."
            ]);
        }

        return new JsonResponse([
            "status" => "success",
            "html" => $this->renderView('modales/reenviarEmailModal.html.twig', [
                'invitacion' => $invitacion,
                'formReenviarCorreo' => $form->createView()
            ])
        ]);
    }


    public function crearUsuarioEnKeycloak($persona, $email, MailerInterface $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $password = substr(md5(uniqid(rand(1, 100))), 1, 6);
        $userByUsernameResponse = $this->intranetService->getUserByUsername($persona->getCuitCuil());
        $userByEmailResponse = $this->intranetService->getUserByEmail($email);
        $existingUserDB = $em->getRepository(User::class)->findByUsernameOrEmail($persona->getCuitCuil(), $email);

        if ((!empty($userByUsernameResponse) || !empty($userByEmailResponse) and $existingUserDB == null)) {
            $this->addFlash('danger', 'Inconsistencia: el usuario existe en keycloak pero no en la DB.');
            return $this->redirectToRoute('dashboard');
        }

        if ((empty($userByUsernameResponse) || empty($userByEmailResponse) and $existingUserDB != null)) {
            $this->addFlash('danger', 'Inconsistencia: el usuario no existe en keycloak pero si en la DB.');
            return $this->redirectToRoute('dashboard');
        }

        if ((empty($userByUsernameResponse) || empty($userByEmailResponse) and $existingUserDB == null)) {
            $data = $this->intranetService->postUser(
                $persona->getCuitCuil(),
                $password,
                $email,
                $persona->getNombres(),
                $persona->getApellido()
            );

            if ($data->getStatusCode() == 500) {
                $this->addFlash('danger', 'Hubo un error, la operación no pudo completarse');
                return $this->redirectToRoute('dashboard');
            }

            $keycloakUser = $this->intranetService->getUserByUsername($persona->getCuitCuil());

            $user = new User;
            $user->setUsername($persona->getCuitCuil());
            $user->setEmail($email);
            $user->setKeycloakId($keycloakUser[0]->id);
            $user->setPassword('');
            $persona->addUser($user);
            $user->setPersonaFisica($persona);

            //Envía un email    
            $url = $this->getParameter('extranet_app_url');
            $templatedEmail = (new TemplatedEmail())
                ->from($this->getParameter('direccion_email_salida'))
                ->to($email)
                ->subject('Invitación a Dispositivo: Datos de acceso')
                ->htmlTemplate('emails/invitacionDatosAcceso.html.twig')
                ->context([
                    'nicname' => $persona->__toString(),
                    'user' => $persona->getCuitCuil(),
                    'password' => $password,
                    'url' => $url
                ]);
            $mailer->send($templatedEmail);

            return $user;
        }

        $this->addFlash('danger', 'Ha ocurrido un error y la opreación no ha podido completarse.');
        return $this->redirectToRoute('dashboard');
    }

    public function asignarGrupos($user, $groups)
    {
        $data = $this->intranetService->postUserGroup(
            $user,
            $groups
        );

        if ($data->getStatusCode() == 500) {
            $this->addFlash('danger', 'Hubo un error, la operación no pudo completarse');
            return $this->redirectToRoute('dashboard');
        }

        return;
    }
}
