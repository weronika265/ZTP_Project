<?php

/**
 * Advertisement fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Advertisement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class AdvertisementFixtures.
 */
class AdvertisementFixtures extends AbstractBaseFixtures
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

        for ($i = 0; $i < 20; ++$i) {
            $advertisement = new Advertisement();
            $advertisement->setName($this->faker->sentence);
            $advertisement->setDescription($this->faker->paragraph);
            //            $advertisement->setPrice(rand(0, 10000000.00));
            $advertisement->setPrice(rand(0, 5000.00));
            $advertisement->setLocation($this->faker->city);
            $advertisement->setDate($this->faker->dateTimeBetween('-30 days', '-1 days'));
            $advertisement->setIsActive(false);
            $this->manager->persist($advertisement);
        }

        $this->manager->flush();
    }
}
