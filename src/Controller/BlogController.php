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
        $posts = $this->getDoctrine()
                        ->getRepository(Post::class)
                        ->findBy([], ['time'=>'DESC']);
        
        $latests = $this->getDoctrine()
                        ->getRepository(Post::class)
                        ->getLatest();
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'latests' => $latests
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_show")
     */
    public function show($slug){
        $post = $this->getDoctrine()
                    ->getRepository(Post::class)
                    ->findOneBy(['slug' => $slug]);

        $latests = $this->getDoctrine()
                    ->getRepository(Post::class)
                    ->getLatest();

        return $this->render('blog/show.html.twig',[
            'post' => $post,
            'latests' => $latests
        ]);
    }


}
