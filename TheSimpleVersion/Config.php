<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

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
