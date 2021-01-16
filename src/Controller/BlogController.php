<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\PostType;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


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
     * @Route("/blog/add", name="blog_add")
     */
    public function add(Request $request)
    {
        $post = new Post();
        $user = $this->getUser();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'), // Le dossier dans le quel le fichier va etre charger
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $post->setPicture($fileName);
            }
            $post->setUser($user);
            $post->setTime(new \DateTime());
            //Utilisation d'un generateur de slug pour remplacer les id
            $slugify = new Slugify();
            $post->setSlug($slugify->slugify($post->getTitle()));
            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
            $em->persist($post); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete

            return $this->redirectToRoute('blog');
        }

    	return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
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

    /**
     * @Route("/posts/{username}", name="user_posts")
     */
    public function renderUserPosts($username){

        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(['username' => $username]);

        $posts = $this->getDoctrine()
                    ->getRepository(Post::class)
                    ->findBy(['user' => $user], ['time' => 'DESC']);

        return $this->render('blog/user_posts.html.twig',[
            'posts' => $posts,
            'user' => $user
        ]);
     }
    
    /**
     * @Route("/blog/edit/{slug}", name="blog_edit")
     */
    public function edit($slug, Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBy(['slug'=> $slug]);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $oldPicture = $post->getPicture();
        
        if ($form->isSubmitted() && $form->isValid()) {

            if ($post->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName = uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $post->setPicture($fileName);
            } else {
                $post->setPicture($oldPicture);
            }

            $slugify = new Slugify();
            $post->setSlug($slugify->slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
        }

    	return $this->render('blog/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/posts/delete/{slug}", name="blog_delete")
     */
    public function remove($slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);
        $entityManager->remove($post);
        $entityManager->flush();
    	return $this->redirectToRoute('blog');
    }


}
