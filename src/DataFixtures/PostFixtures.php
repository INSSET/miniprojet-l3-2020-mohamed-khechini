<?php

namespace App\DataFixtures;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Cocur\Slugify\Slugify;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Utilisation d'un generateur de slug pour remplacer les id
        $slugify = new Slugify();
        //Utilisation des fixtures pour des fausses donées
        for($i=0;$i<15;$i++){
            $post = new Post();
            $post->setTitle("Titre n°".rand(0,100));
            $post->setBody("Voici body Lorem ipsum bla blan°".rand(0,100));
            $post->setTime(new \DateTime());
            $post->setSlug($slugify->slugify($post->getTitle()));

            $manager->persist($post);
        }
        

        $manager->flush();
    }
}
