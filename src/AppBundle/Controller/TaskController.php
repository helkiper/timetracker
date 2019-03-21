<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all task entities.
     *
     * @return JsonResponse
     *
     * @Route("/", options={"expose" = true}, name="task_index")
     */
    public function indexAction()
    {
        return $this->indexPageResponse();
    }

    /**
     * Creates a new task entity.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/new", options={"expose" = true}, name="task_new")
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->indexPageResponse();
        }

        return new JsonResponse([
            'content' =>$this->renderView('task/new.html.twig', [
                'task' => $task,
                'form' => $form->createView(),
            ])
        ]);
    }

    /**
     * Finds and displays a task entity.
     *
     * @param Task $task
     * @return JsonResponse
     *
     * @Route("/{id}", options={"expose" = true}, name="task_show")
     */
    public function showAction(Task $task)
    {
        return $this->showPageResponse($task);
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     *
     * @Route("/{id}/edit", options={"expose" = true}, name="task_edit")
     */
    public function editAction(Request $request, Task $task)
    {
        $editForm = $this->createForm(TaskType::class, $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->showPageResponse($task);
        }

        return new JsonResponse([
            'content' =>$this->renderView('task/edit.html.twig', [
                'task' => $task,
                'edit_form' => $editForm->createView(),
            ])
        ]);
    }

    /**
     * Deletes a task entity.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     *
     * @Route("/{id}/delete", options={"expose" = true}, name="task_delete")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->indexPageResponse();
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', ['id' => $task->getId()]))
            ->getForm()
        ;
    }

    /**
     * @return JsonResponse
     */
    private function indexPageResponse()
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->findAll();

        return new JsonResponse([
            'content' => $this->renderView('task/index.html.twig', [
                'tasks' => $tasks
            ])
        ]);
    }

    /**
     * @param Task $task
     * @return JsonResponse
     */
    private function showPageResponse(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return new JsonResponse([
            'content' => $this->renderView('task/show.html.twig', [
                'task' => $task,
                'delete_form' => $deleteForm->createView(),
            ]),
            'shownTaskId' => $task->getId()
        ]);
    }
}
