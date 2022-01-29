<?php

namespace App\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Room;
use App\Form\RoomType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;


class RoomController extends AbstractFOSRestController 
{
    /**
     * @Route("/api/rooms", name="get_all_rooms"), methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getRepository(Room::class);

        $data = $entityManager->findAll();
        foreach ($data as $d) {
            $res[] = [
                'id' => $d->getId(),
                'name' => $d->getName(),
                'program' => $d->getProgram()
            ];
        }
        $view = $this->view($res, 200);

        return $this->handleView($view);
        //$this->json($res, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/api/room", name="new_room", methods={"POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $room = new Room();
        $form= $this->createForm(RoomType::class, $room);
        $parameter = json_decode($request->getContent(), true);
        //$user->setName($parameter['name']);
        //$user->setStatus($parameter['status']);

        $form->submit($parameter);
        
        if($form->isSubmitted() && $form->isValid())
        {   
            $entityManager = $doctrine->getManager();
            $entityManager->persist($room);
            $entityManager->flush();
            return $this->handleView($this->view(['status'=>'ok'],Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));

        
    }
}