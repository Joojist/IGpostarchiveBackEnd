<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use App\Entity\Post;

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
    $post = new Post();

    // Set the values for the Post entity based on the received data
    $post->setPath($postData['path']);
    $post->setLikes($postData['likes']);
    $post->setCreatorId($postData['creatorId']);
    $post->setIgCreatedAt(new \DateTime($postData['IgCreatedAt']));
    $post->setCreatedAt(new \DateTime);
    $post->setCaption($postData['caption']);
    $post->setTags($postData['tags']);

    // Assuming you have a reference to the IGUser entity based on the $postData['igUserId'] value
    // Example: $igUser = $entityManager->getRepository(IGUser::class)->find($postData['igUserId']);
    $igUser = new \App\Entity\IgUser();
    $igUser->setIgId($postData['igUser']['igId']);
    $igUser->setUsername($postData['igUser']['username']);
    $igUser->setCreatedAt(new \DateTime);

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

    saveMediaFromUrl($mediaItem['url'], $post);
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

/**
 * Save media file from a given URL with a specific naming convention and ensure authorization
 *
 * @param string $url The URL of the media file
 * @param \App\Entity\Post $post The Post entity object
 */
function saveMediaFromUrl($url, $post)
{
    $mediaRoot = __DIR__ .'/images/ig/'; // Change this to the appropriate root path for your media files

    // Generate a unique filename based on the timestamp
    $timestamp = time();
    $uniqueFilename = $timestamp . '.jpg';

    // Create the complete file path
    $filePath = $mediaRoot . $uniqueFilename;

    // Download the media file
    $ch = curl_init($url);
    $fp = fopen($filePath, 'wb');

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    // Ensure the file permissions are set correctly (adjust as needed)
    chmod($filePath, 0644);
}