<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Media;

class MediaController extends AbstractController
{
    private $entityManager;
    private $mediaRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        \Doctrine\Persistence\ObjectRepository $mediaRepository
    ) {
        $this->entityManager = $entityManager;
        $this->mediaRepository = $mediaRepository;
    }

    public function saveMedia(Request $request): Response
    {
        // Retrieve the necessary data from the request
        $mediaData = $request->request->all();

        // Create a new Media entity and set its properties
        $media = new Media();
        $media->setPath($mediaData['path']);
        // ... set other properties

        // Persist the Media entity
        $this->entityManager->persist($media);
        $this->entityManager->flush();

        // Return a response, indicating the success or failure of the operation
        return new Response('Media saved successfully!');
    }

    // Add additional methods for handling other operations related to the Media entity, such as updating, deleting, fetching, etc.
}
