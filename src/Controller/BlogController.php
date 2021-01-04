<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)
                        ->findBy([], ['time'=>'DESC']);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
