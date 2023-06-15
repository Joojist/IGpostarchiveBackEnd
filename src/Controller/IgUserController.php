<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\IgUser;

class IgUserController extends AbstractController
{
    private $entityManager;
    private $igUserRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        \Doctrine\Persistence\ObjectRepository $igUserRepository
    ) {
        $this->entityManager = $entityManager;
        $this->igUserRepository = $igUserRepository;
    }

    public function saveIgUser(Request $request): Response
    {
        // Retrieve the necessary data from the request
        $igUserData = $request->request->all();

        // Create a new IgUser entity and set its properties
        $igUser = new IgUser();
        $igUser->setIgId($igUserData['igId']);
        $igUser->setUsername($igUserData['username']);
        $igUser->setCreatedAt(new \DateTime($igUserData['createdAt']));
        $igUser->setDeletedAt(new \DateTime($igUserData['deletedAt']));
        // ... set other properties

        // Persist the IgUser entity
        $this->entityManager->persist($igUser);
        $this->entityManager->flush();

        // Return a response, indicating the success or failure of the operation
        return new Response('IG User saved successfully!');
    }

    // Add additional methods for handling other operations related to the IgUser entity, such as updating, deleting, fetching, etc.
}
