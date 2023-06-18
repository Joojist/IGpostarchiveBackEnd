<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use App\Entity\Posts;
use App\Entity\Medias;
use App\Entity\Comments;
use App\Entity\IgUsers;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration
$isDevMode = true;
$entitiesPath = [__DIR__."/src"];
$dbParams = include 'config/database.php';

// Doctrine configuration
$config = Setup::createAnnotationMetadataConfiguration($entitiesPath, $isDevMode);
$driver = new AnnotationDriver(new AnnotationReader(), $entitiesPath);
$config->setMetadataDriverImpl($driver);

$entityManager = EntityManager::create($dbParams, $config);

// Check if the HTTP request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data sent from the HTTP request
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Create an instance of Post
    $post = new Posts();

    // Set values for the properties
    $post->setPath($requestData['path']);
    $post->setLikes($requestData['likes']);
    $post->setCreatorId($requestData['creatorId']);
    $post->setUpdaterId($requestData['updaterId']);
    $post->setCaption($requestData['caption']);
    $post->setIgCreatedAt(new \DateTime());
    $post->setCreatedAt(new \DateTime());

    // Create an instance of IgUser
    $igUser = new IgUsers();
    $igUser->setUsername($requestData['username']);
    $igUser->setIgId($requestData['igId']);
    $igUser->setCreatedAt(new \DateTime());

    // Associate the IgUser with the Post
    $post->setIgUser($igUser);

    // Create an ArrayCollection to hold the media entities
    $mediaCollection = new ArrayCollection();

    // Create and add Media entities to the ArrayCollection
    $mediaData = $requestData['media'] ?? [];

    foreach ($mediaData as $mediaItem) {
        $media = new Medias();
        $media->setPath($mediaItem['path']);

        // Associate the Media entity with the Post
        $post->addMedia($media);
    }

    // Create and add Comment entities to the Post
    $commentData = $requestData['comments'] ?? [];

    foreach ($commentData as $commentItem) {
        $comment = new Comments();
        $comment->setContent($commentItem['content']);

        // Associate the Comment entity with the Post
        $post->addComment($comment);

        // Persist the Comment entity
        $entityManager->persist($comment);
    }

    try {
        // Persist the entities
        $entityManager->persist($igUser);
        $entityManager->persist($post);

        // Flush the changes to the database
        $entityManager->flush();

        // Return a success response
        http_response_code(200);
        echo json_encode(['message' => 'Post saved successfully.']);
    } catch (\Exception $e) {
        // Return an error response
        http_response_code(500);
        echo json_encode(['message' => 'Failed to save post: ' . $e->getMessage()]);
    }
} else {
    // Return an error response for unsupported HTTP methods
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
}
