<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Alertas;
use App\Repository\AlertasRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use GuzzleHttp;


class SendAlertsSrv extends AbstractController {
    private $client;
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag) {
        $this->client = new GuzzleHttp\Client();
        $this->parameterBag = $parameterBag;
    }

    /**
     * No está del todo claro, pero 'Usuario' es quien inicia el reporte de incidencia,
     * y 'User' es cada superadministrador
     */
    public function sendIssueReportAlertsToSuperAdmins($issueReport, $usuario) {
        $users = $this->getAllSuperAdminUsers();
        $url = $this->generateUrl('issue_report_show', ['id' => $issueReport->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        foreach ($users as $user) {
            $alerta = new Alertas();
            $alerta->setTitulo('Issue Report de '. $user->getPersonaFisica()->getNombres() . ' ' . $user->getPersonaFisica()->getApellido());
            $alerta->setDescripcion(
                'El usuario '. $usuario->getPersonaFisica()->getNombres() . ' ' . $usuario->getPersonaFisica()->getApellido() . ' ha reportado una incidencia ' .
                'con el siguiente mensaje: ' . $issueReport->getIssue() . '. /n/n' .    ' <a href="' . $url . '">ver Aquí</a>'
                );
            $alerta->setUsuario($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alerta);
        }
        $entityManager->flush();

        return;
    }

    public function sendBadStageAlertToSuperAdmins($escenario) {
        $users = $this->getAllSuperAdminUsers();
        $url = $this->generateUrl('escenarios');
        foreach ($users as $user) {
            $alerta = new Alertas();
            $alerta->setTitulo('Se ha llegado al escenario #' . $escenario);
            $alerta->setDescripcion(
                'El usuario '. $user->getPersonaFisica()->getNombres() . ' ' . $user->getPersonaFisica()->getApellido() . ' ha llegado al escenario #' . $escenario .
                ' <a href="' . $url . '">ver Aquí los escenarios posibles</a>'
                );
            $alerta->setUsuario($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alerta);
        }
        $entityManager->flush();

        return;
    }

    private function getAllSuperAdminUsers() {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->getAllSuperAdminUsers();
        return $users;
    }
}