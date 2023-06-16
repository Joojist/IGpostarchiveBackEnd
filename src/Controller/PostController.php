<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use app\Entity\IgUser;
use App\Entity\Media;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private $entityManager;
    private $postRepository;

    public function __construct(EntityManagerInterface $entityManager, PostRepository $postRepository)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    /**
    * @Route("/post/save", methods={"POST"})
    */
    public function savePost(Request $request): Response
    {
        // Retrieve the data from the request
        $postData = json_decode($request->getContent(), true);

        // Create a new Post entity
        $post = new Post();
        $post->setPath($postData['path']);
        $post->setLikes($postData['likes']);
        $post->setCreatorId($postData['creatorId']);
        $post->setUpdaterId($postData['updaterId']);
        $post->setIgCreatedAt(new \DateTime($postData['igCreatedAt']));
        $post->setCreatedAt(new \DateTime());
        $post->setUpdatedAt(new \DateTime());
        $post->setDeletedAt(new \DateTime());
        $post->setCaption($postData['caption']);
        $post->setTags($postData['tags']);

        // Create and associate the Media entities
        foreach ($postData['media'] as $mediaData) {
            $media = new Media();
            $media->setPath($mediaData['path']);
            // Set other properties of the media entity
            // ...

            $post->addMedia($media);
        }

        // Create and associate the Comment entities
        foreach ($postData['comments'] as $commentData) {
            $comment = new Comment();
            $comment->setContent($commentData['content']);
            // Set other properties of the comment entity
            // ...

            $post->addComment($comment);
        }

        // Create and associate the IGUser entity
        $igUser = new IGUser();
        $igUser->setUsername($postData['igUser']['username']);
        // Set other properties of the IGUser entity
        // ...

        $post->setIgUser($igUser);

        // Persist the entities
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    
        // Return a response indicating success
        return $this->json(['message' => 'Post saved successfully'], Response::HTTP_OK);
    }
    public function getPost($id): Response
    {
        // Find the Post entity by its ID
        $post = $this->postRepository->find($id);

        if (!$post) {
            return new Response('Post not found', Response::HTTP_NOT_FOUND);
        }

        // Return the Post data as a response
        return new Response(sprintf('Post ID: %d, Title: %s, Content: %s', $post->getId(), $post->getTitle(), $post->getContent()));
    }

}
