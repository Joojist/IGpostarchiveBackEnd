<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Headers: Content-Type");
    exit;
}

use App\Entity\Comment;
use App\Entity\IgUser;
use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Reply;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

// Autoload the necessary classes (composer is needed)
require_once "vendor/autoload.php";
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type");

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
    echo json_encode($postData);

    // Create a new Post entity
    $post = new Post();

    // Post values
    $post->setArchiver($postData['archiver']);
    $post->setLikes($postData['likes']);
    $post->setCreatorId($postData['creatorId']);
    $post->setIgCreatedAt(new \DateTime($postData['igCreatedAt']));
    $post->setCreatedAt(new \DateTime());
    $post->setCaption($postData['caption']);
    $post->setTags($postData['tags']);
    $post->setUserComment($postData['userComment']);

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

    // Validate and create Comment entities
    $commentData = $postData['comments'];
    foreach ($commentData as $commentItem) {
        $commentContent = $commentItem['comment'];
        $commentText = $commentContent['text'];
        $repliesData = isset($commentContent['replies']) ? $commentContent['replies'] : [];
        $commentAuthor = $commentContent['author'];
        
        $comment = new Comment();
        $comment->setContent($commentText);
        $comment->setAuthor($commentAuthor);

        // Validate and add Reply entities
        foreach ($repliesData as $replyData) {
            $replyText = $replyData['text'];
            $replyAuthor = $replyData['author'];
        
            $reply = new Reply();
            $reply->setContent($replyText);
            $reply->setAuthor($replyAuthor);
            $reply->setComment($comment);
        
            $entityManager->persist($reply);
            $comment->addReply($reply);
        }

        $entityManager->persist($comment);
        $post->addComment($comment);
    }

    // Persist the Post entity to the database
    $entityManager->persist($post);
    $entityManager->flush();

    // Send a success response
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
    $mediaRoot = __DIR__ . '/images/ig/'; // Change this to the appropriate root path for your media files

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
    $igUser->setCreatedAt(new \DateTime());

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
