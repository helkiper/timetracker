<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/", options={"expose" = true}, name="task_index")
     * @return Response
     *
     * @Route("/", name="task_index")
     */
    public function indexAction()
    {
        return $this->renderIndexPage();
    }

    /**
     * Creates a new task entity.
     *
     * @param Request $request
     * @return Response
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

            return $this->renderIndexPage();
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a task entity.
     *
     * @param Task $task
     * @return Response
     *
     * @Route("/{id}", options={"expose" = true}, name="task_show")
     */
    public function showAction(Task $task)
    {
        return $this->renderShowPage($task);
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @param Request $request
     * @param Task $task
     * @return Response
     *
     * @Route("/{id}/edit", options={"expose" = true}, name="task_edit")
     */
    public function editAction(Request $request, Task $task)
    {
        $editForm = $this->createForm(TaskType::class, $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->renderShowPage($task);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a task entity.
     *
     * @param Request $request
     * @param Task $task
     * @return Response
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

        return $this->renderIndexPage();
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
     * @return Response
     */
    private function renderIndexPage()
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @param Task $task
     * @return Response
     */
    private function renderShowPage(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
        ]);
    }
}
