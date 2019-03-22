<?php
/**
 * Created by PhpStorm.
 * User: Hellkiper
 * Date: 22.03.2019
 * Time: 15:13
 */

namespace AppBundle\Datafixtures;

use AppBundle\Entity\Task;
use AppBundle\Entity\TimeInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TimeIntervalFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * TimeIntervalFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $seed = [];
        for ($i = 0; $i < 20; $i += 2) {
            $seed[$i] = $this->faker->dateTimeBetween('-1 month', 'yesterday');
            $seed[$i+1] = $this->faker->dateTimeInInterval($seed[$i], '+10 hours');
        }

        sort($seed);

        for ($i = 0; $i < 20; $i += 2) {
            $interval = new TimeInterval();
            /** @var Task $task */
            $task = $this->getReference('Task-' . random_int(1, 5));
            if ($task->getCreatedAt() > $seed[$i]) {
                $task->setCreatedAt($this->faker->dateTimeInInterval($seed[$i], '-2 days'));
            }

            $interval->setStartsAt($seed[$i])
                ->setEndsAt($seed[$i+1])
                ->setTask($task)
            ;

            $manager->persist($interval);
        }
        $manager->flush();
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
}