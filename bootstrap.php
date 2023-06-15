<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use App\Entity\Post;

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

$post = new Post();

// Set values for the properties
$post->setLikes(50);
$post->setCreatorId(456);
$post->setUpdaterId(789);
$post->setPath('C');

// Create DateTime objects for date properties
$igCreatedAt = new \DateTime('2023-06-12 10:00:00');
$createdAt = new \DateTime('2023-06-12 10:00:00');
$updatedAt = new \DateTime('2023-06-12 10:00:00');
$deletedAt = new \DateTime('2023-06-12 10:00:00');

// Set date values for date properties
$post->setIgCreatedAt($igCreatedAt);
$post->setCreatedAt($createdAt);
$post->setUpdatedAt($updatedAt);
$post->setDeletedAt($deletedAt);

// ... Perform additional operations with the entity ...

// Persist the entity
$entityManager->persist($post);

// Flush the changes to the database
$entityManager->flush();

