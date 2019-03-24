<?php
/**
 * Created by PhpStorm.
 * User: Hellkiper
 * Date: 24.03.2019
 * Time: 15:23
 */

namespace AppBundle\Datafixtures;

use AppBundle\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    /**
     * CommentFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            TaskFixtures::class
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $comment = new Comment();
            $comment->setComment($this->faker->text);

            /** @noinspection PhpParamsInspection */
            $comment->setTask($this->getReference('Task-' . random_int(1, 5)));
            $manager->persist($comment);
        }
        $manager->flush();
    }
}