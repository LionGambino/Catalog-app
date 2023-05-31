<?php
/**
 * Element fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Enum\ElementStatus;
use App\Entity\Tag;
use App\Entity\Element;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ElementFixtures.
 */
class ElementFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'elements', function (int $i) {
            $element = new Element();
            $element->setTitle($this->faker->sentence);
            $element->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $element->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $element->setCategory($category);

            /** @var Tag $tag */
            $references = 0;
            while (($references) < 3) {
                $tag = $this->getRandomReference('tags');
                $element->addTag($tag);;
                $references=$references+1;
            }

            /** @var User $favourited */
            $references = 0;
            while (($references) < 2) {
                $favourited = $this->getRandomReference('users');
                $element->addFavourited($favourited);;
                $references=$references+1;
            }

            return $element;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
