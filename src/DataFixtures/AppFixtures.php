<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i > 50; $i++) {
			      $user = new User();
			      $user->setName($faker->name);
			      $user->setEmail($faker->email);
    		    $manager->persist($user);
        }
        
        $manager->flush();
    }
}
