<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $wish = new Wish();
            $wish
                ->setTitle($faker->text(20))
                ->setAuthor($faker->text(20))
                ->setDateCreated($faker->dateTimeBetween('-3 year'))
                ->setDescription($faker->text(4000))
                ->setIsPublished($faker->boolean(70));

            $manager->persist($wish);


        }

        $manager->flush();
    }
}
