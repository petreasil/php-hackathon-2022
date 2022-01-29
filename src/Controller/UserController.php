<?php

namespace App\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;


class UserController extends AbstractFOSRestController 
{
    /**
     * @Route("/api/users", name="get_all_users"), methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getRepository(User::class);

        $data = $entityManager->findAll();
        foreach ($data as $d) {
            $res[] = [
                'id' => $d->getId(),
                'name' => $d->getName(),
                'status' => $d->getStatus()
            ];
        }
        $view = $this->view($res, 200);

        return $this->handleView($view);
        //$this->json($res, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/api/user", name="new_user", methods={"POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form= $this->createForm(UserType::class, $user);
        $parameter = json_decode($request->getContent(), true);
        //$user->setName($parameter['name']);
        //$user->setStatus($parameter['status']);

        $form->submit($parameter);
        
        if($form->isSubmitted() && $form->isValid())
        {   
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->handleView($this->view(['status'=>'ok'],Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));

        
    }
}
