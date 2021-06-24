<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Post;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for($nbPost = 1; $nbPost <=30; $nbPost++){
            $category = $this->getReference('category_' . $faker->numberBetween(1, 10));
            $user = $this->getReference('user_' . $faker->numberBetween(1, 20));
            $post = new Post();
            $post->setUser($user);
            $post->setCategory($category);
            $post->setTitle($faker->sentence);
            // $post->setSlug($faker->slug);
            $post->setDescription($faker->paragraph(3));
            $post->setContent($faker->realText(10000));
            $post->setImage($faker->imageUrl(640, 480, 'cats'));
            $post->setActive($faker->numberBetween(0, 1));

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
