<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="get_all_users"), methods={"GET"})
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

        return $this->json($res);
    }

    /**
     * @Route("/user", name="new_user", methods={"POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $parameter = json_decode($request->getContent(), true);
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setName($parameter['name']);
        $user->setStatus($parameter['status']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json('Created new user successfully with id ' . $user->getId());
    }
}
