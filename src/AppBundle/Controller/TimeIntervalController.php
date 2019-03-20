<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TimeInterval;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Timeinterval controller.
 *
 * @Route("timeinterval")
 */
class TimeIntervalController extends Controller
{
    /**
     * Lists all timeInterval entities.
     *
     * @Route("/", name="timeinterval_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $timeIntervals = $em->getRepository('AppBundle:TimeInterval')->findAll();

        return $this->render('timeinterval/index.html.twig', array(
            'timeIntervals' => $timeIntervals,
        ));
    }

    /**
     * Creates a new timeInterval entity.
     *
     * @Route("/new", name="timeinterval_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $timeInterval = new Timeinterval();
        $form = $this->createForm('AppBundle\Form\TimeIntervalType', $timeInterval);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($timeInterval);
            $em->flush();

            return $this->redirectToRoute('timeinterval_show', array('id' => $timeInterval->getId()));
        }

        return $this->render('timeinterval/new.html.twig', array(
            'timeInterval' => $timeInterval,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a timeInterval entity.
     *
     * @Route("/{id}", name="timeinterval_show")
     * @Method("GET")
     */
    public function showAction(TimeInterval $timeInterval)
    {
        $deleteForm = $this->createDeleteForm($timeInterval);

        return $this->render('timeinterval/show.html.twig', array(
            'timeInterval' => $timeInterval,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing timeInterval entity.
     *
     * @Route("/{id}/edit", name="timeinterval_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TimeInterval $timeInterval)
    {
        $deleteForm = $this->createDeleteForm($timeInterval);
        $editForm = $this->createForm('AppBundle\Form\TimeIntervalType', $timeInterval);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('timeinterval_edit', array('id' => $timeInterval->getId()));
        }

        return $this->render('timeinterval/edit.html.twig', array(
            'timeInterval' => $timeInterval,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a timeInterval entity.
     *
     * @Route("/{id}", name="timeinterval_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TimeInterval $timeInterval)
    {
        $form = $this->createDeleteForm($timeInterval);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($timeInterval);
            $em->flush();
        }

        return $this->redirectToRoute('timeinterval_index');
    }

    /**
     * Creates a form to delete a timeInterval entity.
     *
     * @param TimeInterval $timeInterval The timeInterval entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TimeInterval $timeInterval)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('timeinterval_delete', array('id' => $timeInterval->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
