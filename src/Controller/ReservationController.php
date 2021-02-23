<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{

    /**
     * @Route("/{idreservation}/export", name="reservation_export")
     */
    public function export(ReservationRepository $reservationRepository, $idreservation)
    {
        $pdfOptions = new Options();
        $pdfOptions->setDefaultFont('Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create(
            [
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed'=> TRUE
                ]
            ]
        );
        $dompdf->setHttpContext($context);

        $html = $this->render('reservation/export.html.twig', [
            'reservations' => $reservationRepository->findBy(
                ['id' => $idreservation]
            ),
        ]);
        $dompdf->loadHtml($html->getContent());
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();

        $fichier = 'billet-'.$this->getUser()->getNom().'-'.$this->getUser()->getPrenom().'-'.'.pdf';

        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }

    /**
     * @Route("/{idreservation}/valider", name="reservation_valider")
     * @param ReservationRepository $reservationRepository
     * @param $idreservation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function valider(ReservationRepository  $reservationRepository, $idreservation)
    {
         $reservationRepository->valider($idreservation);
        return $this->redirectToRoute('reservation_index');
    }

    /**
     * @Route("/{idreservation}/{idseance}/{nbr}/cancel", name="reservation_cancel", methods={"GET","POST"})
     */
    public function cancel(ReservationRepository $reservationRepository, $idseance, $idreservation, $nbr)
    {
        $reservationRepository->cancel($idseance, $nbr, $idreservation);
        return $this->redirectToRoute('reservation_index');
    }

    /**
     * @Route("/consult", name="reservation_consult")
     * @param ReservationRepository $reservationRepository
     * @return Response
     */
    public function consult(ReservationRepository $reservationRepository, Security $security): Response
    {
        $date = new \DateTime();
        $user = new User();
        $user = $security->getUser();
        return $this->render('reservation/consult.html.twig',[
            'reservations' => $reservationRepository->findBy(
            ['idUser'=> $user]
            ),
            'date'=> $date
        ]);
    }

    /**
     * @Route("/", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $utilisateur = new User();
        $utilisateur = $security->getUser();
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setIdUser($utilisateur);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index');
    }


}
