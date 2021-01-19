<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends AbstractController
{
    /**
     * @Route("/comment/add", name="comment_add")
     */
    public function add(Request $request)
    {
        $post_id = $request->request->get('post_id');
        $user = $this->getUser();
        $post = $this->getDoctrine()
                     ->getRepository(Post::class)
                     ->find($post_id);
        
        $comment = new Comment();
        $comment->setBody($request->request->get('_body'));
        $comment->setUser($user);
        $comment->setPost($post);
        $comment->setCreated(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        $post_slug = $post->getSlug();

        return $this->redirectToRoute('blog_show', [
            'slug' => $post_slug,
        ]);
    }

    /**
     * @Route("/comments/delete/{id}", name="comment_delete")
     */
    public function remove($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->findOneBy(['id' => $id]);
        $entityManager->remove($comment);
        $entityManager->flush();
    	return $this->redirectToRoute('blog');
    }
}