<?php

/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(5, 'categories', function () {
            $category = new Category();
            $category->setName($this->faker->unique()->word);
            $this->manager->persist($category);

            return $category;
        });

        $this->manager->flush();
    }
}
