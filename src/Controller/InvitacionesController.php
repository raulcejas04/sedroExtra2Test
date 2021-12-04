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
                $entityManager->persist($persona);
            } else {
                $invitacion->setPersonaFisica($persona);
            }
            //TODO: Crear usuario en keycloak y asignar valores al usuario.
            if (!$persona->getUser()) {
                $password = substr(md5(uniqid(rand(1, 100))), 1, 6);
                //Crea usuario en keycloak y en la tabla usuarios
                $user = $this->crearUsuario($persona, $form["email"]->getData(), $password);
                //Envía un email    
                $url = $this->getParameter('extranet_app_url');
                $email = (new TemplatedEmail())
                    ->from($this->getParameter('direccion_email_salida'))
                    ->to($form["email"]->getData())
                    ->subject('Invitación a Dispositivo: Datos de acceso')
                    ->htmlTemplate('emails/invitacionDatosAcceso.html.twig')
                    ->context([
                        'nicname' => $persona->__toString(),
                        'user' => $persona->getCuitCuil(),
                        'password' => $password,
                        'url' => $url
                    ]);
                $mailer->send($email);
            } else {
                if ($persona->getUser()->getEmail() != $form['email']->getData()) {
                    $this->addFlash('danger', "El correo ingresado es diferente al que posee el usuario. Se envió el correo a: {$persona->getUser()->getEmail()}");
                }
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
                    'dispositivo' => $invitacion->getDispositivo()->getNicname(),
                    'hash' => $invitacion->getHash()
                ]);

            $mailer->send($email);

            //  dd($invitacion);
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

    public function crearUsuario($persona, $email, $password)
    {
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

        $user = new User;
        $user->setUsername($persona->getCuitCuil());
        $user->setEmail($email);
        $user->setPassword('');
        $persona->setUser($user);
        $user->setPersonaFisica($persona);
        return $user;
    }
}
