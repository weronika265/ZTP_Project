<?php

/**
 * Advertisement fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Advertiser;
use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class AdvertisementFixtures.
 */
class AdvertisementFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(30, 'advertisements', function (int $i) {
            $advertisement = new Advertisement();
            $advertisement->setName($this->faker->sentence);
            $advertisement->setDescription($this->faker->paragraph);
            //            $advertisement->setPrice(rand(0, 10000000.00));
            $advertisement->setPrice(rand(0, 5000.00));
            $advertisement->setLocation($this->faker->city);
            $advertisement->setDate($this->faker->dateTimeBetween('-30 days', '-1 days'));
            $advertisement->setIsActive(false);

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $advertisement->setCategory($category);

            /** @var Advertiser $adveriser */
            $adveriser = $this->getRandomReference('advertisers');
            $advertisement->setAdvertiser($adveriser);

            return $advertisement;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: AdvertiserFixtures}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, AdvertiserFixtures::class];
    }
}
