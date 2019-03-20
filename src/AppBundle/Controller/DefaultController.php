<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", options={"expose" = true}, name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}
