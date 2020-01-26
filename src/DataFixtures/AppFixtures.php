<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\ManagerConfigurator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
//        $manager = $fixture->load(Product::class);

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($faker->name)
                ->setPrice($faker->randomFloat(2,1,999))
                ->setDescription($faker->text(350))
                ->setSlug($faker->slug())
                ->setCreatedAt($faker->dateTime())
//                ->setImage($faker->imageUrl(400, 400))
            ;
            $manager->persist($product);
        }

        $manager->flush();
    }
}
