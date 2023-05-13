<?php

/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Faker.
     */
    protected Generator $faker;

    /**
     * Persistence object manager.
     */
    protected ObjectManager $manager;

    /**
     * Load.
     */
    public function loadData(): void
    {
        $this->faker = Factory::create();

        for ($i = 0; $i < 3; ++$i) {
            $category = new Category();
            $category->setName($this->faker->name);
            $this->manager->persist($category);
        }

        $this->manager->flush();
    }
}
