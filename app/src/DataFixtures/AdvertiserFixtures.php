<?php

/**
 *  Advertiser fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Advertiser;

/**
 * Category AdvertiserFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class AdvertiserFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(25, 'advertisers', function (int $i) {
            $advertiser = new Advertiser();
            $advertiser->setEmail($this->faker->email);
            $advertiser->setPhone($this->faker->e164PhoneNumber);
            $advertiser->setName($this->faker->name);

            return $advertiser;
        });

        $this->manager->flush();
    }
}