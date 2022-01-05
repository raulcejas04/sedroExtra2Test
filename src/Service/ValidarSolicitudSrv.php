<?php

namespace App\Service;

use App\Entity\Dispositivo;
use App\Entity\DispositivoResponsable;
use App\Entity\Realm;
use App\Entity\UsuarioDispositivo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp;
use App\Service\KeycloakApiSrv;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\AuxSrv;
use DateTime;

class ValidarSolicitudSrv extends AbstractController
{
    private $client;
    private $parameterBag;
    private $kc;
    private $em;
    private $auxSrv;
    private $sendAlertsSrv;

    public function __construct(ParameterBagInterface $parameterBag, KeycloakApiSrv $kc, EntityManagerInterface $em, SendAlertsSrv $sendAlertsSrv, AuxSrv $auxSrv)
    {
        $this->client = new GuzzleHttp\Client();
        $this->parameterBag = $parameterBag;
        $this->kc = $kc;
        $this->em = $em;
        $this->sendAlertsSrv = $sendAlertsSrv;
        $this->auxSrv = $auxSrv;
    }

    //public function validarSolicitud($solicitud){
    // public function validarSolicitud($personaFisica, $personaJuridica, $dispositivo, $usuario, $usuarioDispositivo, $ambiente = 'Intranet', $paso = '1')
    public function validarSolicitud($solicitud, $ambiente = 'Intranet', $paso = '1')
    {
        $realm = $this->getDoctrine()->getRepository(Realm::class)->findOneBy(['realm' => $this->getParameter('keycloak_realm')]);
        $personaFisica = $this->em->getRepository('App:PersonaFisica')->findOneBy(['cuitCuil' => $solicitud->getCuil()]);
        $personaJuridica = $this->em->getRepository('App:PersonaJuridica')->findOneBy(['cuit' => $solicitud->getCuit()]);
        $dispositivo =  $this->em->getRepository('App:Dispositivo')->findOneBy(['nicname' => $solicitud->getNicname(), 'personaJuridica' => $personaJuridica]);  //TODO:A futuro otro parametro para dispositivo va a ser Localidad
        //TODO:Filtrar su correo tambien. El error actual es que si existe otro user con el mismo correo en el mismo realm, tira error en KC.
        //Se podría arreglar validando antes el email de la solicitud.
        $usuario =  $this->em->getRepository('App:User')->findOneBy(['username' => $solicitud->getCuil(), 'realm' => $realm]);
        $usuarioDispositivo = $this->em->getRepository('App:UsuarioDispositivo')->findOneBy(["usuario" => $usuario, "dispositivo" => $dispositivo]);

        // dd($personaFisica,$personaJuridica,$dispositivo,$usuario,$usuarioDispositivo);

        $flagOk = false;
        $redirectForError = false;
        $data = null;
        $message = "";

        //El debugMode habilita los flash para saber a que escenario entró el validador.
        //Poner en false en producción.
        $debugMode = true;

        /**
         * escenario 1:
         * Paso 1: Error no deja seguir si está vigente
         * Paso 2: 
         * Paso 3:
         * Inicia: Extranet
         * Observaciones: Error si no expiró
         */
        if ($personaFisica && $personaJuridica && $dispositivo && $usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 1 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Intranet':
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                        case '2':
                        case '3':
                            $message = 'No se puede continuar con la solicitud, ya que el dispositivo tiene asociado a la persona y su usuario.';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                    }
                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 2:
         * Paso 1: traer los datos
         * Paso 2: traer los datos
         * Paso 3: alta usuario_dispositivo
         * Inicia: Extranet
         * Observaciones: Trabaja en otro dispositivo
         */
        if ($personaFisica && $personaJuridica && $dispositivo && $usuario && !$usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 2: Trabaja en otro dispositivo');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 2 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '2':
                            $flashOk = true;
                            $redirectForError = false;
                            $data = [
                                'personaFisica' => $personaFisica,
                                'personaJuridica' => $personaJuridica,
                                'dispositivo' => $dispositivo,
                                'usuario' => $usuario,
                                'usuarioDispositivo' => $usuarioDispositivo,
                                'ambiente' => $ambiente,
                                'paso' => $paso,
                            ];
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '3':
                            //TODO: Paso 3 deberia ir en extra. lo dejo acá hasta refactorizar la extra
                            $this->auxSrv->createUsuarioDispositivo($dispositivo, $usuario);
                            $message = 'El usuario, la persona física, la persona jurídica y el dispositivo existían con anterioridad.';
                            $message .= 'Se ha vinculado el usuario ' . $usuario->getPersonaFisica()->getNombres() . ' ' . $usuario->getPersonaFisica()->getApellido() . '(' . $usuario->getUsername() . ')' . ' a ' . $dispositivo->getNicname();
                            break;
                    }

                    break;
            }
        }

        /**
         * escenario 3: INCONSISTENCIA
         * Paso 1: Sino existe el usuario, usuario_dispositivo no existe
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && $personaJuridica && $dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 3 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('3');

            return $salida;
        }

        /**
         * escenario 4:
         * Paso 1: Traer datos
         * Paso 2: Traer datos
         * Paso 3: Alta Usuario
         * Inicia: Extranet
         * Observaciones: Paciente
         */
        if ($personaFisica && $personaJuridica && $dispositivo && !$usuario && !$usuarioDispositivo) {
            // $this->addFlash('danger', 'Escenario # 4: Paciente');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 4 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;

                        case '2':
                            $datos = [
                                'personaFisica' => $personaFisica,
                                'personaJuridica' => $personaJuridica,
                                'dispositivo' => $dispositivo,
                                'usuario' => null,
                                'usuarioDispositivo' => null,
                                'ambiente' => $ambiente,
                                'paso' => $paso,
                            ];
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;

                            //return $datos;
                            break;

                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                        case '2':
                        case '3':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            //Crea un nuevo usuario de Extranet en KC, en la DB y envía un email con los datos de acceso
                            /*   $this->auxSrv->createKeycloakcAndDatabaseUser($personaFisica, $solicitud, 'Extranet');
                            $this->auxSrv->createUsuarioDispositivo($dispositivo,$solicitud->getUsuario(),UsuarioDispositivo::NIVEL_2); */
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;
                            break;
                    }

                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 5:
         * Paso 1: Traer datos
         * Paso 2: Traer datos
         * Paso 3: Alta Dispositivo
         * Inicia: Intranet
         * Observaciones: El usuario existe en otro dispositivo
         */
        if ($personaFisica && $personaJuridica && !$dispositivo && $usuario && $usuarioDispositivo) {
            //TODO: Revisar inconsistencia ? usuarioDispositivo depende de dispositivo. por ende esto nunca se cumpliria
            // $this->addFlash('danger', 'Escenario # 5: El usuario existe en otro dispositivo');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 5 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                        case '2':
                            # code...
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;

                            break;
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;

                            break;
                        case '3':
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;

                            break;
                    }

                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 6:
         * Paso 1: Traer datos
         * Paso 2: Traer datos
         * Paso 3: Alta Dispositivo y usuario_dispositivo
         * Inicia: Intranet
         * Observaciones: El usuario existe en otro dispositivo y se da de alta en un dispositivo nuevo
         */
        if ($personaFisica && $personaJuridica && !$dispositivo && $usuario && !$usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 6: El usuario existe en otro dispositivo y se da de alta en un dispositivo nuevo');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 6 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message =  'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                        case '2':
                            $solicitud->setPersonaFisica($personaFisica);
                            $solicitud->setPersonaJuridica($personaJuridica);
                            $solicitud->setFechaUso(new \DateTime('now'));
                            $solicitud->setUsada(true);
                            $message = 'Datos completados con Exito.';
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                            $hash = md5(uniqid(rand(), true));
                            $solicitud->setHash($hash);
                            $solicitud->setPersonaFisica($personaFisica);
                            $solicitud->setPersonaJuridica($personaJuridica);
                            $solicitud->setUsuario($usuario);
                            $this->auxSrv->EnviarCorreoInvitacion($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = 'Invitación generada correctamente.';
                            break;
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '3':
                            $this->auxSrv->createDispositivoAndUserDispositivo($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = "Invitación aceptada correctamente. El usuario, la persona física y la persona jurídica existían con anterioridad.<br/>";
                            $message .= "Se ha creado el Dispositivo y se ha relacionado el usuario responsable con exito.";
                            break;
                    }

                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 7: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && $personaJuridica && !$dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 7 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('7');

            return $salida;
        }

        /**
         * escenario 8:
         * Paso 1: Traer datos
         * Paso 2: Traer datos
         * Paso 3: Traer datos
         * Inicia: Intranet
         * Observaciones: Alta de Dispositivo, Usuario y UsuarioDispositivo
         */
        if ($personaFisica && $personaJuridica && !$dispositivo && !$usuario && !$usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 8: Paciente en un dispositivo nuevo');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 8 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '2':
                            $solicitud->setPersonaJuridica($personaJuridica);
                            $solicitud->setPersonaFisica($personaFisica);
                            $solicitud->setFechaUso(new \DateTime('now'));
                            $solicitud->setUsada(true);
                            $message = 'Datos completados con Exito.';
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            //$data = null;
                            //$solicitud = null;

                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                            $hash = md5(uniqid(rand(), true));
                            $solicitud->setHash($hash);
                            $solicitud->setPersonaFisica($personaFisica);
                            $solicitud->setPersonaJuridica($personaJuridica);
                            $this->auxSrv->EnviarCorreoInvitacion($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = 'Invitación generada correctamente.';
                            break;
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;

                            break;
                        case '3':
                            $this->auxSrv->createKeycloakcAndDatabaseUser($personaFisica, $solicitud, 'Extranet');
                            $this->auxSrv->createDispositivoAndUserDispositivo($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = "Dispositivo y Usuario creados correctamente.";
                            break;
                    }

                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 9: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && !$personaJuridica && $dispositivo && $usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 9 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('9');

            return $salida;
        }

        /**
         * escenario 10: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && !$personaJuridica && $dispositivo && $usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 10 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('10');

            return $salida;
        }

        /**
         * escenario 11: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && !$personaJuridica && $dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 11 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('11');

            return $salida;
        }

        /**
         * escenario 12: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && !$personaJuridica && $dispositivo && !$usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 12 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('12');

            return $salida;
        }

        /**
         * escenario 13: INCOSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && !$personaJuridica && !$dispositivo && $usuario && $usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 13: INCOSISTENCIA');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 13 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('13');
            return $salida;
        }

        /**
         * escenario 14:
         * Paso 1: Traer datos
         * Paso 2: Traer datos
         * Paso 3: alta persona jurídica, dispositivo, usuario dispositivo
         * Inicia: Intranet
         * Observaciones: trabaja en otro dispositivo de otra razón social
         */
        if ($personaFisica && !$personaJuridica && !$dispositivo && $usuario && !$usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 14: trabaja en otro dispositivo de otra razón social');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 14 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '2':
                            $solicitud->setPersonaFisica($personaFisica);
                            $solicitud->setFechaUso(new \DateTime('now'));
                            $solicitud->setUsada(true);
                            $message = 'Datos completados con Exito.';
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                            $hash = md5(uniqid(rand(), true));
                            $solicitud->setHash($hash);
                            $solicitud->setPersonaFisica($personaFisica);
                            $solicitud->setUsuario($usuario);
                            $this->auxSrv->EnviarCorreoInvitacion($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = 'Invitación generada correctamente.';
                            break;
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                        case '3':
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;

                            break;
                    }

                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 15: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if ($personaFisica && !$personaJuridica && !$dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 15 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('15');

            return $salida;
        }

        /**
         * escenario 16:
         * Paso 1: Traer datos
         * Paso 2: Traer datos
         * Paso 3: alta persona jur, dispositivo, usuario, usuario_dispositivo
         * Inicia: Intranet
         * Observaciones:
         */
        if ($personaFisica && !$personaJuridica && !$dispositivo && !$usuario && !$usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 16: alta persona jur, dispositivo, usuario, usuario_dispositivo');

            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '2':
                            $solicitud->setPersonaFisica($personaFisica);
                            $solicitud->setFechaUso(new \DateTime('now'));
                            $solicitud->setUsada(true);
                            $message = 'Datos completados con Exito.';
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                            $hash = md5(uniqid(rand(), true));
                            $solicitud->setHash($hash);
                            $solicitud->setPersonaFisica($personaFisica);
                            $this->auxSrv->EnviarCorreoInvitacion($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = 'Invitación generada correctamente.';
                            break;
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                        case '3':
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                    }

                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 17: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && $dispositivo && $usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 17 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('17');

            return $salida;
        }

        /**
         * escenario 18: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && $dispositivo && $usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 18 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('18');

            return $salida;
        }

        /**
         * escenario 19: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && $dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 19 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('19');

            return $salida;
        }

        /**
         * escenario 20: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && $dispositivo && !$usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 20 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('20');

            return $salida;
        }

        /**
         * escenario 21: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && !$dispositivo && $usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 21 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('21');

            return $salida;
        }

        /**
         * escenario 22: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && !$dispositivo && $usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 22 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('22');

            return $salida;
        }

        /**
         * escenario 23: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && !$dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 23 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('23');

            return $salida;
        }

        /**
         * escenario 24: 
         * Paso 1: Traer datos Persona Jurídica
         * Paso 2: Traer datos
         * Paso 3: Alta persona fisica, dispositivo, usuario, usuario_dispositivo
         * Inicia: Intranet
         * Observaciones:
         */
        if (!$personaFisica && $personaJuridica && !$dispositivo && !$usuario && !$usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 24: Alta persona fisica, dispositivo, usuario, usuario_dispositivo');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 24 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '2':
                            $solicitud->setPersonaJuridica($personaJuridica);
                            $solicitud->setFechaUso(new \DateTime('now'));
                            $solicitud->setUsada(true);
                            $message = 'Datos completados con Exito.';
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                            $hash = md5(uniqid(rand(), true));
                            $solicitud->setHash($hash);
                            $solicitud->setPersonaJuridica($personaJuridica);
                            $this->auxSrv->EnviarCorreoInvitacion($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = 'Invitación generada correctamente.';
                            break;
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                    }

                    break;
            }

            //return $this->redirectToRoute('dashboard');
        }

        /**
         * escenario 25: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && $dispositivo && $usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 25 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('25');

            return $salida;
        }

        /**
         * escenario 26: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && $dispositivo && $usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 26 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('26');

            return $salida;
        }

        /**
         * escenario 27: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && $dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 27 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('27');

            return $salida;
        }

        /**
         * escenario 28: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && $dispositivo && !$usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 28 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('28');

            return $salida;
        }

        /**
         * escenario 29: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && !$dispositivo && $usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 29 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('29');

            return $salida;
        }

        /**
         * escenario 30: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && !$dispositivo && $usuario && !$usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 30 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('30');

            return $salida;
        }

        /**
         * escenario 31: INCONSISTENCIA
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia:
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && !$dispositivo && !$usuario && $usuarioDispositivo) {
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 31 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            $salida = $this->accionesSobreInconsistencias('31');

            return $salida;
        }

        /**
         * escenario 32:
         * Paso 1:
         * Paso 2: 
         * Paso 3:
         * Inicia: Intranet
         * Observaciones:
         */
        if (!$personaFisica && !$personaJuridica && !$dispositivo && !$usuario && !$usuarioDispositivo) {
            //$this->addFlash('danger', 'Escenario # 32: Alta persona fisica, dispositivo, usuario, usuario_dispositivo');
            if ($debugMode) {
                $this->addFlash('success', 'Escenario: 32 - Paso: ' . $paso . ' - Ambiente: ' . $ambiente);
            }
            switch ($ambiente) {
                case 'Extranet':
                    switch ($paso) {
                        case '1':
                            $message = 'Sin permisos suficientes para iniciar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;
                            break;
                        case '2':
                            $solicitud->setFechaUso(new \DateTime('now'));
                            $solicitud->setUsada(true);
                            $message = 'Datos completados con Exito.';
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            break;
                        case '3':
                            $message = 'Sin permisos suficientes para aceptar o rechazar una solicitud';
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $solicitud = null;

                            break;
                    }

                    break;

                case 'Intranet':
                    switch ($paso) {
                        case '1':
                            $hash = md5(uniqid(rand(), true));
                            $solicitud->setHash($hash);
                            $this->auxSrv->EnviarCorreoInvitacion($solicitud);
                            $flagOk = true;
                            $redirectForError = false;
                            $data = null;
                            $message = 'Invitación generada correctamente.';
                            break;
                        case '2':
                            $message = 'Seccion destinada a invitados en la extranet por solicitud. Si crees que es un error contacta a soporte <a href="{{ path("issue_report_new") }}>haciendo clic aquí y danos un poco de contexto.</a>';
                            //$flagOk = false;
                            //$redirectForError = true;
                            //$data = null;
                            //$solicitud = null;

                            break;
                        case '3':
                            $flagOk = false;
                            $redirectForError = true;
                            $data = null;
                            $message = "Ha ocurrido un error.";
                            break;
                    }

                    break;
            }
        }

        $salida = [
            'flagOk' => $flagOk,
            'redirectForError' => $redirectForError,
            'data' => $data,
            'message' => $message,
            'solicitud' => $solicitud,
        ];

        return $salida;
    }

    private function accionesSobreInconsistencias($escenario)
    {
        $this->sendAlertsSrv->sendBadStageAlertToSuperAdmins($escenario);
        $message = 'Escenario # ' . $escenario . ' con inconsistencias. Se ha comunicado a soporte';

        $salida = [
            'flagOk' => false,
            'redirectForError' => true,
            'data' => null,
            'message' => $message,
            'solicitud' => null,
        ];

        return $salida;
    }
}
