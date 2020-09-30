<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Faker;
use App\Entity\Category;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        // On configure dans quelles langues nous voulons nos données

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 6; $i++) {
            $category = new Category();
            $category->setName($faker->name);
            $manager->persist($category);

            for ($j = 0; $j < 10; $j++) {
                $product = new Product();

                $product->setName($faker->name)
                        ->setSKU($faker->swiftBicNumber)
                        ->setColor($faker->hexcolor)
                        ->setPrice($faker->randomNumber(2))
                        ->setImage($faker->imageUrl($width = 800, $height = 600, 'nightlife'))
                        ->setCreateAt(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')))
                        ->setUpdatedAt(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')))
                        ->setDescription($faker->text($maxNbChars = 200));
                $product->setCategory($category);


                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
