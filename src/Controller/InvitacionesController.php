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
use App\Entity\User;
use App\Entity\UsuarioDispositivo;
use App\Form\InvitacionType;
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
            "hash" => $hash
        ]);
        $response = $this->renderView('invitaciones/verInvitacion.html.twig', [
            'invitacion' => $invitacion,
        ]);

        return new Response($response);
    }

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
                $this->addFlash('error', 'No puedes invitarte a ti mismo.');
                return $this->redirectToRoute('nueva_invitacion');
            }

            if ($this->getDoctrine()->getRepository(Invitacion::class)->findOneBy(['personaFisica' => $invitacion->getPersonaFisica(), 'dispositivo' => $invitacion->getDispositivo()])) {
                $this->addFlash('error', 'Ya has enviado una invitación a esta persona.');
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

        if (!$persona->getUser()) {
            // $password = substr(md5(uniqid(rand(1, 100))), 1, 6);
            //Crea usuario en keycloak y en la tabla usuarios
            $this->crearUsuario($persona, $invitacion->getEmail(), $mailer);
        } else {
            if ($persona->getUser()->getEmail() != $invitacion->getEmail()) {
                $this->addFlash('danger', "El correo ingresado es diferente al que posee el usuario. Se envió el correo a: {$persona->getUser()->getEmail()}");
            }
        }

        //Asignamos el usuario al dispositivo
        $dispositivo = $invitacion->getDispositivo();
        $usuarioDispositivo = new UsuarioDispositivo();
        $usuarioDispositivo->setDispositivo($dispositivo);
        $usuarioDispositivo->setUsuario($persona->getUser());
        //TODO:Inyectar este seteo mediante un servicio o función
        $usuarioDispositivo->setFechaAlta(new DateTime());
        $dispositivo->addUsuarioDispositivo($usuarioDispositivo);

        //TODO: No hardcodear, traer desde dispositivo->tipoDispositivo->grupos
        //Hardcodeado para testear
        $groups = ["Administradores"];
        $this->asignarGrupos($persona->getUser(), $groups);

        $em->persist($invitacion);
        $em->flush();

        $this->addFlash('success', 'Invitación confirmada correctamente.');
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


    public function crearUsuario($persona, $email, MailerInterface $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $password = substr(md5(uniqid(rand(1, 100))), 1, 6);
        $userByUsernameResponse = $this->intranetService->getUserByUsername($persona->getCuitCuil());
        $userByEmailResponse = $this->intranetService->getUserByEmail($email);

        if (empty($userByUsernameResponse) || empty($userByEmailResponse)) {
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
        }

        $existingUser = $em->getRepository(User::class)->findByUsernameOrEmail($persona->getCuitCuil(), $email);
        $keycloakUser = $this->intranetService->getUserByUsername($persona->getCuitCuil());

        if (!$existingUser) {
            $user = new User;
            $user->setUsername($persona->getCuitCuil());
            $user->setEmail($email);
            $user->setKeycloakId($keycloakUser[0]->id);
            $user->setPassword('');
            $persona->setUser($user);
            $user->setPersonaFisica($persona);
            return $user;
        } else {
            $existingUser->setKeycloakId($keycloakUser[0]->id);
            $persona->setUser($existingUser);
            $existingUser->setPersonaFisica($persona);
            return $existingUser;
        }
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
