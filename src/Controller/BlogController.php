<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $articles = $this->getDoctrine()
                        ->getRepository(Post::class)
                        ->findBy([],['time' => 'DESC']);

        $posts = $paginator->paginate(
            $articles, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        
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
