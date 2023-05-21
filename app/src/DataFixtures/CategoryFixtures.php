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
        $this->createMany(5, 'categories', function (int $i) {
            $category = new Category();
            $category->setName($this->faker->unique()->name);
            $this->manager->persist($category);

            return $category;
        });

        $this->manager->flush();
    }
}
