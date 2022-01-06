<?php

namespace App\Controller;

use App\Entity\IssueReport;
use App\Form\IssueReportType;
use App\Entity\Alertas;
use App\Repository\IssueReportRepository;
use App\Service\SendAlertsSrv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('dashboard/issue-report')]
class IssueReportController extends AbstractController
{

    public function __construct(SendAlertsSrv $sendAlertsSrv){
    	$this->sendAlertsSrv = $sendAlertsSrv;
    }

    #[Route('/new', name: 'issue_report_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $issueReport = new IssueReport();
        $form = $this->createForm(IssueReportType::class, $issueReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            
            $this->sendAlertsSrv->sendIssueReportAlertsToSuperAdmins($issueReport, $user);
            
            $issueReport->setUsuario($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($issueReport);
            $entityManager->flush();
            $this->addFlash('success', 'Gracias por reportar un problema. Lo solucionaremos lo antes posible');
            return $this->redirectToRoute('solicitudes', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('issue_report/new.html.twig', [
            'issue_report' => $issueReport,
            'form' => $form,
        ]);

    }
    
}
