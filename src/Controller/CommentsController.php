<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;

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
     * @Route("/comment/edit/{id}", name="comment_edit")
     */
    public function edit($id, Request $request){
        $comment = $this->getDoctrine()->getRepository(Comment::class)->findOneBy(['id'=> $id]);
        
        $user = $this->getUser();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUser($user);
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        
        return $this->render('blog/comment_edit.html.twig', [
            'comment'=> $comment,
            'form' => $form->createView()
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