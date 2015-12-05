<?php

namespace Pixelbonus\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     */
    public function indexAction() {
        return $this->render('PixelbonusSiteBundle:Default:index.html.twig', array());
    }

    /**
     * @Route("/courses", name="courses")
     * @Secure(roles="ROLE_USER")
     */
    public function coursesAction() {
        return $this->render('PixelbonusSiteBundle:Courses:courses.html.twig', array());
    }
}
