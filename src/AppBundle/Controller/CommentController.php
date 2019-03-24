<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Comment controller.
 *
 * @Route("comment")
 */
class CommentController extends Controller
{
    /**
     * Creates a new comment entity.
     *
     * @param Request $request
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{task}/new", name="comment_new")
     */
    public function newAction(Request $request, Task $task)
    {
        $comment = new Comment();
        $form = $this->createForm(
            'AppBundle\Form\CommentType',
            $comment, [
                'action' => $this->generateUrl(
                    'comment_new',
                    ['task' => $task->getId()]
                )
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTask($task);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->render('comment/list_raw.html.twig', [
                'comment' => $comment
            ]);
        }

        return $this->render('comment/new.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
            'task' => $task
        ));
    }
}
