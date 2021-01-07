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
        
    }
}
