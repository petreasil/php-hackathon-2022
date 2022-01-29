<?php

namespace App\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ReservationController extends AbstractFOSRestController
{
    /**
     * @Route("/api/users/{id}/reservation", name="get_user_reservation"), methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $userId = $request->get('id');
        $entityManager = $doctrine->getRepository(User::class);

        $data = $entityManager->findOneBy(['id' => $userId]);

        if (!$data) {
            throw new NotFoundHttpException('user not found');
        }

        $reservation = $doctrine->getRepository(Reservation::class)->findOneBy([
            'user' => $data,
        ]);

        if (!$reservation) {
            throw new NotFoundHttpException('Reservation does not exist for this user');
        }

        $view = $this->view($data, 200);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/users/reservation", name="new_reservation", methods={"POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $parameter = json_decode($request->getContent(), true);


        $form->submit($parameter);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * @Route("/api/users/{userId}/reservation/{reservationId}", name="delete_reservation", methods={"DELETE"})
     */
    public function deleteOption(Request $request, ManagerRegistry $doctrine): Response
    {
        $userId = $request->get('userId');
        $reservationId = $request->get('reservationId');

        $reservation = $doctrine->getRepository(Reservation::class)->findOneBy([
            'id' => $reservationId,
            'user' => $userId
        ]);

        if (!$reservation) {
            throw new NotFoundHttpException('Reservation does not exist!!!');
        }
        $entityManager = $doctrine->getManager();
        $entityManager->remove($reservation);
        $entityManager->flush();


        return new Response('Record  ' . $reservation . ' was deleted ', Response::HTTP_OK);
    }
}
