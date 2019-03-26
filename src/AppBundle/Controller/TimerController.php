<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\TimeInterval;
use AppBundle\Utils\TimeIntervalSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TimerController
 *
 * @Route("/timer")
 */
class TimerController extends Controller
{
    /**
     * @var TimeIntervalSessionManager
     */
    private $sessionManager;

    /**
     * TimerController constructor.
     * @param TimeIntervalSessionManager $sessionManager
     */
    public function __construct(TimeIntervalSessionManager$sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param Task $task
     * @return JsonResponse
     * @throws \Exception
     *
     * @Route("/start/{task}", name="timer_start")
     */
    public function startAction(Task $task)
    {
        $interval = new TimeInterval();
        $interval->setTask($task);

        $this->sessionManager->set($interval);

        return new JsonResponse([
            'content' => $this->renderView('timer/active.html.twig', [
                'task' => $task
            ]),
            'timerData' => ['value' => 0]
        ]);
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     *
     * @Route("/stop", name="timer_stop")
     */
    public function stopAction()
    {
        //todo flashBag
        $interval = $this->sessionManager->get();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($interval);
        $entityManager->flush();

        $this->sessionManager->clear();

        return new JsonResponse([
            'timerData' => ['stop' => true]
        ]);
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     *
     * @Route("/get-state", options={"expose"=true}, name="timer_state")
     */
    public function stateAction()
    {
        if ($this->sessionManager->isStored()) {
            $interval = $this->sessionManager->get();

            return new JsonResponse([
                'content' => $this->renderView('timer/active.html.twig', [
                    'task' => $interval->getTask()
                ]),
                'timerData' => ['value' => $interval->getDuration()]
            ]);
        }

        return new JsonResponse();
    }
}
