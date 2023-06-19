<?php

use App\Entity\Comment;
use App\Entity\IgUser;
use App\Entity\Media;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use App\Entity\Post;

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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = file_get_contents('php://input');

    // Parse the data as JSON
    $postData = json_decode($data, true);

    // Create a new Post entity
    $post = new Post();

    // Post values
    $post->setPath($postData['path']);
    $post->setLikes($postData['likes']);
    $post->setCreatorId($postData['creatorId']);
    $post->setIgCreatedAt(new \DateTime);
    $post->setCreatedAt(new \DateTime);
    $post->setCaption($postData['caption']);
    $post->setTags($postData['tags']);

    // Validate and add IGUser entity
    $igUser = validateIgUser($postData['igUser']);
    if ($igUser === null) {
        $response = ['error' => 'Invalid IGUser data'];
        echo json_encode($response);
        return;
    }
    $entityManager->persist($igUser);
    $post->setIgUser($igUser);

    // Validate and add Media entities
    $mediaData = $postData['media'];
    foreach ($mediaData as $mediaItem) {
        $media = validateMedia($mediaItem);
        if ($media === null) {
            $response = ['error' => 'Invalid media data'];
            echo json_encode($response);
            return;
        }
        $entityManager->persist($media);
        $post->addMedia($media);
        saveMediaFromUrl($media->getPath(), $post);
    }

    // Validate and add Comment entities
    $commentData = $postData['comments'];
    foreach ($commentData as $commentItem) {
        $comment = validateComment($commentItem);
        if ($comment === null) {
            $response = ['error' => 'Invalid comment data'];
            echo json_encode($response);
            return;
        }
        $entityManager->persist($comment);
        $post->addComment($comment);
    }

    // Persist the Post entity to the database
    $entityManager->persist($post);
    $entityManager->flush();

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

    echo "Media saved: " . $filePath . "\n";
}

/**
 * Validate and create an IGUser entity
 *
 * @param array $igUserData The IGUser data
 * @return \App\Entity\IgUser|null The validated IGUser entity or null if validation fails
 */
function validateIgUser($igUserData)
{
    if (!isset($igUserData['igId']) || !isset($igUserData['username'])) {
        return null;
    }

    $igUser = new IgUser();
    $igUser->setIgId($igUserData['igId']);
    $igUser->setUsername($igUserData['username']);
    $igUser->setCreatedAt(new \DateTime);

    return $igUser;
}

/**
 * Validate and create a Media entity
 *
 * @param array $mediaData The media data
 * @return \App\Entity\Media|null The validated Media entity or null if validation fails
 */
function validateMedia($mediaData)
{
    if (!isset($mediaData['url'])) {
        return null;
    }

    $media = new Media();
    $media->setPath($mediaData['url']);

    return $media;
}

/**
 * Validate and create a Comment entity
 *
 * @param array $commentData The comment data
 * @return \App\Entity\Comment|null The validated Comment entity or null if validation fails
 */
function validateComment($commentData)
{
    if (!isset($commentData['text'])) {
        return null;
    }

    $comment = new Comment();
    $comment->setContent($commentData['text']);

    return $comment;
}
