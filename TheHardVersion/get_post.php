<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

// Create a simple Doctrine ORM configuration
$isDevMode = true;
$entitiesPath = [__DIR__.'/src'];

// Database configuration
$dbParams = [
    'driver' => 'pdo_pgsql',
    'host' => 'localhost',
    'port' => '5432',
    'dbname' => 'Folklore',
    'user' => 'postgres',
    'password' => 'postgres',
];

// Doctrine configuration
$config = Setup::createAnnotationMetadataConfiguration($entitiesPath, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

// Retrieve posts from the database
$postRepository = $entityManager->getRepository(App\Entity\Post::class);
$posts = $postRepository->findAll();

// Convert the retrieved posts to an array
$postsArray = [];
foreach ($posts as $post) {
    $postsArray[] = [
        'id' => $post->getId(),
        'title' => $post->getTitle(),
        'content' => $post->getContent(),
        // Add other properties as needed
    ];
}

// Output the posts as JSON
header('Content-Type: application/json');
echo json_encode($postsArray);
