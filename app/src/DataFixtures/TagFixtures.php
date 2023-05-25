<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use DateTimeImmutable;

/**
 * Class TagFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'tags', function (int $i) {
            $category = new Tag();
            $category->setTitle($this->faker->unique()->word);
            $category->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $category->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $category;
        });

        $this->manager->flush();
    }
}