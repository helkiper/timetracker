<?php
/**
 * Created by PhpStorm.
 * User: Hellkiper
 * Date: 22.03.2019
 * Time: 8:51
 */

namespace AppBundle\Utils;


use AppBundle\Entity\Task;
use AppBundle\Entity\TimeInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TimeIntervalSessionManager
{
    private static $TRACKED_TASK_ID = 'trackedTaskId';
    private static $STARTED_AT = 'startedAt';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * TimeIntervalSessionManager constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * @param TimeInterval $interval
     */
    public function set(TimeInterval $interval)
    {
        $this->session->set(self::$TRACKED_TASK_ID, $interval->getTask()->getId());
        $this->session->set(self::$STARTED_AT, $interval->getStartsAt());
    }

    /**
     * @return TimeInterval|null
     * @throws \Exception
     */
    public function get()
    {
        if ($this->isStored()) {
            $interval = new TimeInterval();

            $interval->setStartsAt($this->session->get(self::$STARTED_AT))
                ->setTask($this->entityManager->find(
                    Task::class,
                    $this->session->get(self::$TRACKED_TASK_ID)
                ))
            ;

            return $interval;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isStored()
    {
        return $this->session->has(self::$TRACKED_TASK_ID)
            && $this->session->has(self::$STARTED_AT);
    }

    public function clear()
    {
        $this->session->remove(self::$TRACKED_TASK_ID);
        $this->session->remove(self::$STARTED_AT);
    }
}