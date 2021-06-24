<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for($nbCategory = 1; $nbCategory <= 10; $nbCategory++){
            $category = new Category();
            $category->setParent(NULL);
            $category->setName($faker->word);
            // $category->setSlug($faker->slug);
            $manager->persist($category);
            $this->addReference('category_' . $nbCategory, $category);
        }

        $manager->flush();
    }
}
