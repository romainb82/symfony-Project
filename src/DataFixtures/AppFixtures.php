<?php

namespace App\DataFixtures;

use App\Entity\Todo;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $todo = array();
        for ($i = 0; $i <= 50; $i++) {
            $todo[$i] = new Todo();
            $todo[$i]->setName($faker->name);
            $todo[$i]->setDescription('Lorem Ipsum');

            $manager->persist($todo[$i]);
        }
        $manager->flush();
    }
}