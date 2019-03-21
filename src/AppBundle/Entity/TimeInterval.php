<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimeInterval
 *
 * @ORM\Table(name="time_interval")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimeIntervalRepository")
 */
class TimeInterval
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startsAt", type="datetime")
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endsAt", type="datetime")
     */
    private $endsAt;

    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Task", inversedBy="timeIntervals")
     */
    private $task;

    /**
     * TimeInterval constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->startsAt = new \DateTime();
        $this->endsAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startsAt
     *
     * @param \DateTime $startsAt
     *
     * @return TimeInterval
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * Get startsAt
     *
     * @return \DateTime
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * Set endsAt
     *
     * @param \DateTime $endsAt
     *
     * @return TimeInterval
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * Get endsAt
     *
     * @return \DateTime
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param Task $task
     * @return TimeInterval
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->endsAt->getTimestamp() - $this->startsAt->getTimestamp();
    }
}
