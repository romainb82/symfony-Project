<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use App\Entity\Todo;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;

use Faker;

class AppFixtures extends Fixture
{
    private $registry = null;
    public function __construct(ManagerRegistry $registry){
        $this->registry = $registry;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $todo = array();
        $doctrine = $this->registry->getRepository(Priority::class);
        $idPriority = $doctrine->find(1);

        for ($i = 0; $i <= 50; $i++) {
            $todo[$i] = new Todo();
            $todo[$i]->setName($faker->name);
            $todo[$i]->setDescription('Lorem Ipsum');
            $todo[$i]->setPriority($idPriority);

            $manager->persist($todo[$i]);
        }
        $manager->flush();
    }
}