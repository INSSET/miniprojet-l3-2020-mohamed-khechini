<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager){
        $this->loadUsers($manager);
        $this->loadPosts($manager);
    }
    public function loadPosts(ObjectManager $manager)
    {
        //Utilisation d'un generateur de slug pour remplacer les id
        $slugify = new Slugify();
        //Utilisation des fixtures pour des fausses donées
        for($i=0;$i<15;$i++){
            $post = new Post();
            $post->setTitle("Titre n°".rand(0,100));
            $post->setBody("Voici body Lorem ipsum bla blan°".rand(0,100));
            $post->setTime(new \DateTime());
            $post->setUser($this->getReference('khechini'));
            $post->setSlug($slugify->slugify($post->getTitle()));

            $manager->persist($post);
        }
        

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager){
            $user = new User();
            $user->setUsername("khechinimed");
            $user->setFullname("Khechini Mohamed");
            $user->setEmail("khechinibakr20@gmail.com");
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user, 'baker_780'
                )
            );
            $this->addReference('khechini', $user);
            $manager->persist($user);
        
        

        $manager->flush();
    }
}
