<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

// Autoload the necessary classes (assuming Composer is used)
require_once "vendor/autoload.php";

$isDevMode = true;
$entitiesPath = [__DIR__."/src"];
$dbParams = include 'config/database.php';

$config = Setup::createAnnotationMetadataConfiguration($entitiesPath, $isDevMode);
$driver = new AnnotationDriver(new AnnotationReader(), $entitiesPath);
$config->setMetadataDriverImpl($driver);

// Create the EntityManager
$entityManager = EntityManager::create($dbParams, $config);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = file_get_contents('php://input');
    
    // Parse the data as JSON
    $postData = json_decode($data, true);

    // Create a new Post entity
    $post = new \App\Entity\Post();

    // Set the values for the Post entity based on the received data
    $post->setPath($postData['path']);
    $post->setLikes($postData['likes']);
    $post->setCreatorId($postData['creatorId']);
    $post->setIgCreatedAt(new \DateTime);
    $post->setCreatedAt(new \DateTime);
    $post->setCaption($postData['caption']);
    $post->setTags($postData['tags']);

    // Assuming you have a reference to the IGUser entity based on the $postData['igUserId'] value
    // Example: $igUser = $entityManager->getRepository(IGUser::class)->find($postData['igUserId']);
    $igUser = new \App\Entity\IgUser();
    $igUser->setIgId($postData['igUser']['igId']);
    $igUser->setUsername($postData['igUser']['username']);
    $igUser->setCreatedAt(new \DateTime);
    $igUser->setDeletedAt(new \DateTime);

    $entityManager->persist($igUser);
    $entityManager->flush();

    // Associate the new IGUser entity with the Post entity
    $post->setIgUser($igUser);

    // Loop through the media data and create Media entities
    // Loop through the media data and create Media entities
$mediaData = $postData['media'];
foreach ($mediaData as $mediaItem) {
    $media = new \App\Entity\Media();
    $media->setPath($mediaItem['url']);
    // Set other properties of the Media entity as needed
    // ...

    // Add the Media entity to the Post
    $post->addMedia($media);

    // Persist the Media entity
    $entityManager->persist($media);
}

// Persist the Post entity to the database
$entityManager->persist($post);
$entityManager->flush();


    // Loop through the comment data and create Comment entities
    $commentData = $postData['comments'];
    foreach ($commentData as $commentItem) {
        $comment = new \App\Entity\Comment();
        $comment->setContent($commentItem['text']);
        // Set other properties of the Comment entity as needed
        // ...

        // Add the Comment entity to the Post
        $post->addComment($comment);

        $entityManager->persist($comment);
    }

    // Persist the Post entity to the database
    $entityManager->persist($post);
    $entityManager->flush();

    // Return a response to Postman
    $response = ['success' => true];
    echo json_encode($response);
} else {
    // Return an error response if the request method is not POST
    $response = ['error' => 'Invalid request method'];
    echo json_encode($response);
}
