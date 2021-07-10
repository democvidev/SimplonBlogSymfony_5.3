<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for($nbUser = 1; $nbUser <= 20; $nbUser++){
            $user = new User;
            $user->setEmail($faker->email);
            if($nbUser === 1) {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }
            $user->setPassword($this->encoder->hashPassword($user, '123456'));
            $user->setIsVerified($faker->numberBetween(0, 1));
            $manager->persist($user);
            $this->addReference('user_' . $nbUser, $user);
        }

        $manager->flush();
    }
}
