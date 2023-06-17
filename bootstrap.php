<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\Posts;
use App\Entity\Medias;
use App\Entity\Comments;
use App\Entity\IgUsers;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;


require_once "vendor/autoload.php";

$dbParams = include 'config/database.php';
$paths = [
    __DIR__ . '/src/Entity',
    __DIR__."/src"
];

foreach ($paths as $path) {
    if (!is_dir($path)) {
        throw new \InvalidArgumentException(sprintf('Entity path "%s" does not exist or is not a directory.', $path));
    }
}

// Set up configuration
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$config->setProxyDir(__DIR__ . '/Proxies'); // Assuming the "Proxies" directory is at the same level as the bootstrap.php file
$config->setProxyNamespace('Proxies');

// Create annotation driver
$annotationDriver = new AnnotationDriver(new AnnotationReader(), false);
AnnotationRegistry::loadAnnotationClass('class_exists');
$config->setMetadataDriverImpl($annotationDriver);

$entityManager = EntityManager::create($dbParams, $config);

try {
    // Check if the connection is successful
    $connection = $entityManager->getConnection();
    $connection->connect();
    echo "Connected to the database!" . PHP_EOL;

    // Create an instance of SchemaTool
    $schemaTool = new SchemaTool($entityManager);

    // Get the entity metadata
    $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

    // Update the database schema (create tables if they don't exist)
    $schemaTool->updateSchema($metadata);

    // Display a message in the terminal indicating successful table creation or update
    echo "Schema updated successfully!" . PHP_EOL;
} catch (\Exception $e) {
    // Display an error message in the terminal if table creation or update fails
    echo "Failed to update schema: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Create a new Posts entity
$post = new Posts();
$post->setPath('example.jpg');
$post->setLikes(0);
$post->setCreatorId(1);
$post->setIgCreatedAt(new \DateTime());
$post->setCreatedAt(new \DateTime());
$post->setCaption('Example post');
$post->setTags(['tag1', 'tag2']);

// Create a new Media entity and add it to the post
$media = new Medias();
$media->setPath('example.jpg');
$post->addMedia($media);

// Create a new IgUsers entity and associate it with the post
$igUser = new IgUsers();
$igUser->setUsername('john_doe');
$post->setIgUser($igUser);

// Create a new Comment entity and add it to the post
$comment1 = new Comments();
$comment1->setContent('This is the first comment');
$post->addComment($comment1);

$comment2 = new Comments();
$comment2->setContent('This is the second comment');
$post->addComment($comment2);

// Save the post
try {
    $entityManager->persist($post);
    $entityManager->flush();
    echo "Post saved successfully with ID: " . $post->getId() . PHP_EOL;
} catch (\Exception $e) {
    echo "Failed to save the post: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
    exit(1);
}

