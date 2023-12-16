<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * This code is responsible for loading city data into the database.
     */
    public function load(ObjectManager $manager): void
    {
        $cities = ['Antananarivo', 'Antsiranana', 'Fianarantsoa', 'Mahajanga', 'Toamasina', 'Toliara'];
        foreach ($cities as $city) {
            $cityEntity = new City();
            $cityEntity
                ->setName($city)
                ->setLabel($city);
            $manager->persist($cityEntity);
        }
        $manager->flush();
    }
}
