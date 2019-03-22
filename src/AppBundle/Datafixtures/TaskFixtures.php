<?php
/**
 * Created by PhpStorm.
 * User: Hellkiper
 * Date: 22.03.2019
 * Time: 15:02
 */

namespace AppBundle\Datafixtures;

use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * TaskFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 6; $i++) {
            $task = new Task();
            $task->setName($this->faker->jobTitle)
                ->setDescription($this->faker->text());

            $this->addReference('Task-' . $i, $task);
            $manager->persist($task);
        }

        $manager->flush();
    }
}