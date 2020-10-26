<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\ZoneDeMarquage;
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

        // nombre de user
        for ($i = 0; $i < $faker->numberBetween($min = 1, $max = 5); $i++) {

            $user = new User();

            $user->setEmail($faker->email)
                ->setRoles(["ROLE_USER"])
                ->setPassword($faker->password)
                ->setNumberTVA($faker->vat(false))
                ->setIsVerified($faker->numberBetween($min = 0, $max = 1))
                ->setGroupclient($faker->numberBetween($min = 1, $max = 2));

            $manager->persist($user);
        }

        // nombre de category
        for ($i = 0; $i < 6; $i++) {

            $category = new Category();

            $category->setName($faker->lastName);
            $manager->persist($category);

            // nombre de product
            for ($j = 0; $j < $faker->numberBetween($min = 1, $max = 10); $j++) {

                $product = new Product();
                $zonedemarquage = new ZoneDeMarquage();

                // nombre de color
                $color = [];
                $quantity = [];
                $image = [];
                for ($l = 0; $l < $faker->numberBetween($min = 1, $max = 6); $l++) {
                    array_push($color, $faker->hexcolor);
                    array_push($quantity, "'" . $faker->numberBetween($min = 1, $max = 9000) . "'");
                    //array_push($image, $faker->imageUrl($width = 640, $height = 480, 'nightlife'));
                    array_push($image, 'http://placeimg.com/640/480/any');
                }

                //$data = serialize($color);

                $product->setName($faker->company)
                    ->setSKU($faker->swiftBicNumber)
                    ->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 2, $max = 200))
                    //format array pour la couleur
                    ->setColor(implode("','", $color))
                    ->setImage(implode("','", $image))
                    ->setCreateAt(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')))
                    ->setUpdatedAt(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')))
                    ->setDescription($faker->text($maxNbChars = 100))
                    ->setQuantity(implode("','", $quantity));
                $product->setCategory($category);

                $zonedemarquage->setHeight($faker->numberBetween($min = 100, $max = 300))
                    ->setWidth($faker->numberBetween($min = 100, $max = 500))
                    ->setLeftSpace($faker->numberBetween($min = 50, $max = 100))
                    ->setTopSpace($faker->numberBetween($min = 50, $max = 100));
                $zonedemarquage->setProduct($product);

                $manager->persist($zonedemarquage);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
