<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;

class CommentController extends AbstractController
{
    private $entityManager;
    private $commentRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        \Doctrine\Persistence\ObjectRepository $commentRepository
    ) {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }

    public function saveComment(Request $request): Response
    {
        // Retrieve the necessary data from the request
        $commentData = $request->request->all();

        // Create a new Comment entity and set its properties
        $comment = new Comment();
        $comment->setContent($commentData['content']);
        // ... set other properties

        // Persist the Comment entity
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        // Return a response, indicating the success or failure of the operation
        return new Response('Comment saved successfully!');
    }

    // Add additional methods for handling other operations related to the Comment entity, such as updating, deleting, fetching, etc.
}
