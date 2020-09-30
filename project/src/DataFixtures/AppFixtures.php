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

                $color =[];
                for ($l = 0; $l < 3 ; $l++) {
                    array_push($color,$faker->hexcolor);
                }
                $data = serialize($color);
                $product->setName($faker->name)
                        ->setSKU($faker->swiftBicNumber)
                        ->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 2, $max = 200))
                        ->setColor(implode("','",$color))
                        ->setImage($faker->imageUrl($width = 640, $height = 480, 'nightlife'))
                        ->setCreateAt(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')))
                        ->setUpdatedAt(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')))
                        ->setDescription($faker->text($maxNbChars = 200))
                        ->setQuantity($faker->numberBetween($min = 1, $max = 9000));
                $product->setCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
