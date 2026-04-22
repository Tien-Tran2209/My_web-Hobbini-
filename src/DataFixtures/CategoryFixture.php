<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CategoryFixture extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['category']; 
    }

    public function load(ObjectManager $manager): void
    {
        $cat1 = new Category();
        $cat1->setName('Chaussures');
        $manager->persist($cat1);

        $cat2 = new Category();
        $cat2->setName('Montre');
        $manager->persist($cat2);

        $manager->flush();

        echo "Categories created!\n";
    }
}