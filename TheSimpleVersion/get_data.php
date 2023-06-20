<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

// Autoload the necessary classes (composer is needed)
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
    // Extract the relevant data from the Post entity
    $postData = [
        'path' => $post->getArchiver(),
        'likes' => $post->getLikes(),
        'creatorId' => $post->getCreatorId(),
        'igCreatedAt' => $post->getIgCreatedAt()->format('Y-m-d H:i:s'),
        'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
        'caption' => $post->getCaption(),
        'tags' => $post->getTags(),
        'userComment' => $post->getUserComment(),
    ];

    // Extract IGUser data
    $igUser = $post->getIgUser();
    $igUserData = [
        'igId' => $igUser->getIgId(),
        'username' => $igUser->getUsername(),
    ];
    $postData['igUser'] = $igUserData;

    // Extract Media data
    $mediaData = [];
    $mediaEntities = $post->getMedia();
    foreach ($mediaEntities as $mediaEntity) {
        $mediaData[] = [
            'url' => $mediaEntity->getPath(),
        ];
    }
    $postData['media'] = $mediaData;

    // Extract Comment data
    $commentData = [];
    $commentEntities = $post->getComments();
    foreach ($commentEntities as $commentEntity) {
        $commentContent = [
            'text' => $commentEntity->getContent(),
            'author' => $commentEntity->getAuthor(),
        ];

        // Extract Reply data
        $replyData = [];
        $replyEntities = $commentEntity->getReplies();
        foreach ($replyEntities as $replyEntity) {
            $replyData[] = [
                'text' => $replyEntity->getContent(),
                'author' => $replyEntity->getAuthor(),
            ];
        }
        $commentContent['replies'] = $replyData;

        $commentData[] = [
            'comment' => $commentContent,
        ];
    }
    $postData['comments'] = $commentData;


    $responseData[] = $postData;
}

// Return the response as JSON
echo json_encode($responseData);
