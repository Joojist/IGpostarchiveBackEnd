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

// Retrieve all posts from the database
$posts = $entityManager->getRepository(\App\Entity\Post::class)->findAll();

// Prepare the response data
$responseData = [];

foreach ($posts as $post) {
    $postData = [
        'id' => $post->getId(),
        'path' => $post->getPath(),
        'likes' => $post->getLikes(),
        'creatorId' => $post->getCreatorId(),
        'igCreatedAt' => $post->getIgCreatedAt()->format('Y-m-d H:i:s'),
        'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
        'caption' => $post->getCaption(),
        'tags' => $post->getTags(),
        'userComment'=> $post->getUserComment(),
        'igUser' => [
            'igId' => $post->getIgUser()->getIgId(),
            'username' => $post->getIgUser()->getUsername(),
            'createdAt' => $post->getIgUser()->getCreatedAt()->format('Y-m-d H:i:s'),
            'deletedAt' => $post->getIgUser()->getDeletedAt()->format('Y-m-d H:i:s')
        ],
        'media' => [],
        'comments' => []
    ];

    foreach ($post->getMedia() as $media) {
        $postData['media'][] = [
            'url' => $media->getPath()
            // Add other media properties as needed
        ];
    }

    foreach ($post->getComments() as $comment) {
        $postData['comments'][] = [
            'text' => $comment->getContent()
            // Add other comment properties as needed
        ];
    }

    $responseData[] = $postData;
}

// Return the response as JSON
echo json_encode($responseData);
